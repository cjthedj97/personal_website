function generateIPv4() {
    // Array of documentation IP address ranges
    const ranges = [
        { start: 192, second: 0, third: 2 }, // 192.0.2.0/24
        { start: 198, second: 51, third: 100 }, // 198.51.100.0/24
        { start: 203, second: 0, third: 113 } // 203.0.113.0/24
    ];

    // Select a random range
    const range = ranges[Math.floor(Math.random() * ranges.length)];

    // Generate the last octet as a random number between 0 and 255
    const lastOctet = Math.floor(Math.random() * 256);

    // Construct the IP address
    const ip = `${range.start}.${range.second}.${range.third}.${lastOctet}`;

    // Display the generated IP address
    document.getElementById("ipDisplay4").innerText = "Generated IPv4: " + ip;
}