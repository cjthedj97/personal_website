function generateIPv6() {
    // Base for documentation IPv6 addresses
    const base = "2001:DB8:";
    
    // Generate the rest of the IPv6 parts
    let ipv6 = base;
    for (let i = 0; i < 6; i++) {
        // Generate a random segment for the IPv6 address
        let segment = Math.floor(Math.random() * 65536).toString(16);
        ipv6 += i === 0 ? segment : ":" + segment;
    }

    // Display the generated IPv6 address
    document.getElementById("ipDisplay6").innerText = "Generated IPv6: " + ipv6;
}