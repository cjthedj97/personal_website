const ALLOWED_URLS = new Set([
  'https://www.spacejam.com/1996/', 'https://alistapart.com/', 'https://www.apple.com/',
  'https://www.wired.com/', 'https://www.geocities.com/', 'https://home.netscape.com/',
  'https://www.imdb.com/', 'https://www.yahoo.com/', 'https://www.orkut.com/',
  'https://vine.co/', 'https://www.friendster.com/', 'https://dir.yahoo.com/',
  'https://www.aim.com/', 'https://wave.google.com/', 'https://www.stumbleupon.com/',
  'https://del.icio.us/', 'https://www.altavista.com/', 'https://www.clubpenguin.com/',
  'https://messenger.msn.com/', 'https://video.google.com/', 'https://spaces.live.com/',
  'https://www.bebo.com/', 'https://www.xanga.com/', 'https://www.webshots.com/',
  'https://answers.yahoo.com/', 'https://pipes.yahoo.com/', 'https://encarta.msn.com/',
  'https://www.napster.com/', 'https://www.eworld.com/'
]);

function jsonResponse(body, status = 200, headers = {}) {
  return new Response(JSON.stringify(body), {
    status,
    headers: { 'Content-Type': 'application/json; charset=utf-8', ...headers }
  });
}

export async function onRequestGet({ request, waitUntil }) {
  const requestUrl = new URL(request.url);
  const siteUrl = requestUrl.searchParams.get('url');
  const targetYear = requestUrl.searchParams.get('timestamp');

  if (!siteUrl || !/^\d{4}$/.test(targetYear || '')) {
    return jsonResponse({ error: 'Provide a site URL and four-digit target year.' }, 400);
  }

  let parsedSite;
  try { parsedSite = new URL(siteUrl); }
  catch { return jsonResponse({ error: 'Invalid site URL.' }, 400); }

  const currentYear = new Date().getUTCFullYear();
  if (parsedSite.protocol !== 'https:' || !ALLOWED_URLS.has(parsedSite.href) ||
      parsedSite.username || parsedSite.password || parsedSite.port || parsedSite.hash) {
    return jsonResponse({ error: 'That site is not in the curated archive list.' }, 403);
  }
  if (Number(targetYear) < 1996 || Number(targetYear) > currentYear) {
    return jsonResponse({ error: `Choose a year from 1996 through ${currentYear}.` }, 400);
  }

  parsedSite.hostname = parsedSite.hostname.toLowerCase();
  const cacheKeyUrl = new URL(request.url);
  cacheKeyUrl.pathname = '/__lost-web-cache/snapshot';
  cacheKeyUrl.search = new URLSearchParams({ url: parsedSite.href, year: targetYear }).toString();
  const cacheKey = new Request(cacheKeyUrl.toString());
  const cache = caches.default;
  try {
    const cached = await cache.match(cacheKey);
    if (cached) return cached;
  } catch (_) {
    // Continue without cache if the runtime cache is unavailable.
  }

  const cdxUrl = new URL('https://web.archive.org/cdx/search/cdx');
  cdxUrl.searchParams.set('url', parsedSite.href);
  cdxUrl.searchParams.set('from', targetYear);
  cdxUrl.searchParams.set('to', targetYear);
  cdxUrl.searchParams.set('output', 'json');
  cdxUrl.searchParams.set('fl', 'timestamp,original,statuscode');
  cdxUrl.searchParams.set('filter', 'statuscode:200');
  cdxUrl.searchParams.set('limit', '1');

  let upstream;
  try {
    upstream = await fetch(cdxUrl, { headers: { Accept: 'application/json' } });
  } catch (_) {
    return jsonResponse({ error: 'The Internet Archive could not be reached. Try again shortly.' }, 502, { 'Cache-Control': 'no-store' });
  }
  if (upstream.status === 429) {
    return jsonResponse({ error: 'The Internet Archive is rate-limiting lookups. Please try again shortly.' }, 503, { 'Cache-Control': 'no-store', 'Retry-After': '30' });
  }
  if (!upstream.ok) {
    return jsonResponse({ error: `The Internet Archive returned HTTP ${upstream.status}.` }, 502, { 'Cache-Control': 'no-store' });
  }

  let rows;
  try { rows = await upstream.json(); }
  catch { return jsonResponse({ error: 'The Internet Archive returned an unreadable response.' }, 502, { 'Cache-Control': 'no-store' }); }
  if (Array.isArray(rows) && rows.length === 0) rows = [['timestamp', 'original', 'statuscode']];
  if (!Array.isArray(rows) || !Array.isArray(rows[0])) {
    return jsonResponse({ error: 'The Internet Archive returned an unexpected response.' }, 502, { 'Cache-Control': 'no-store' });
  }

  const columns = rows[0];
  const timestampIndex = columns.indexOf('timestamp');
  const originalIndex = columns.indexOf('original');
  const statusIndex = columns.indexOf('statuscode');
  if (timestampIndex < 0 || originalIndex < 0 || statusIndex < 0) {
    return jsonResponse({ error: 'The Internet Archive returned an unexpected response.' }, 502, { 'Cache-Control': 'no-store' });
  }
  const captureRows = rows.slice(1);
  if (captureRows.some(row => !Array.isArray(row) || typeof row[timestampIndex] !== 'string' ||
      !/^\d{14}$/.test(row[timestampIndex]) || typeof row[originalIndex] !== 'string' ||
      typeof row[statusIndex] !== 'string')) {
    return jsonResponse({ error: 'The Internet Archive returned an unexpected capture.' }, 502, { 'Cache-Control': 'no-store' });
  }
  const capture = captureRows.find(row =>
    row[timestampIndex].slice(0, 4) === targetYear && row[statusIndex] === '200');
  let payload = { archived_snapshots: {} };

  if (capture) {
    let originalUrl;
    try { originalUrl = new URL(capture[originalIndex]); }
    catch { return jsonResponse({ error: 'The Internet Archive returned an invalid capture URL.' }, 502, { 'Cache-Control': 'no-store' }); }
    const timestamp = capture[timestampIndex];
    if (!/^\d{14}$/.test(timestamp || '') || !['http:', 'https:'].includes(originalUrl.protocol) ||
        originalUrl.hostname !== parsedSite.hostname || originalUrl.pathname !== parsedSite.pathname ||
        originalUrl.username || originalUrl.password || originalUrl.port || originalUrl.search || originalUrl.hash) {
      return jsonResponse({ error: 'The Internet Archive returned an unexpected capture.' }, 502, { 'Cache-Control': 'no-store' });
    }
    // Keep the original scheme inside Wayback's HTTPS replay path; it identifies the archived capture.
    payload = { archived_snapshots: { closest: {
      available: true,
      timestamp,
      url: `https://web.archive.org/web/${timestamp}/${capture[originalIndex]}`
    } } };
  }

  const response = jsonResponse(payload, 200, { 'Cache-Control': 'public, max-age=0, s-maxage=21600' });
  try {
    if (waitUntil) waitUntil(cache.put(cacheKey, response.clone()));
    else await cache.put(cacheKey, response.clone());
  } catch (_) {
    // A cache failure should not turn a successful lookup into an error.
  }
  return response;
}
