// One-off: swap the warm editorial palette for the clinical blue one (2026-09-27).
// Kept in the repo so the mapping is documented and reversible (swap the pairs).
const fs = require("fs");
const map = {
  "#F7F4ED": "#FFFFFF", "#EFEAE0": "#F1F6FB", "#EDE7DB": "#EAF2FA", "#F0E4D7": "#E3EEF9",
  "#E6D5C3": "#CFE0F2", "#F4F0E7": "#F7FAFD", "#14241E": "#0B2540", "#3D4C45": "#33475E",
  "#4A5A53": "#4A5D73", "#BAC4BE": "#B9C8DA", "#E4DED0": "#E1E8F0", "#D6CFBF": "#D0DAE6",
  "#C9C1B1": "#B9C6D5", "#BDB5A4": "#A9B8C9", "#C2603A": "#1668B8", "#A44C29": "#0F5C9C",
  "#D49075": "#6FA7DC", "#E7B8A3": "#B8D5F0", "#0B1713": "#06182B", "#8E3F20": "#0B4A7E",
  "#DEA389": "#8DBBE6", "#F0EBE1": "#EEF3F8", "#2E4239": "#22384F", "#24382F": "#1A3149",
  "#A39A88": "#93A3B6", "#A9BDB6": "#A7BACD",
  "rgb(247 244 237": "rgb(255 255 255", "rgb(20 36 30": "rgb(11 37 64",
};
const files = ["assets/css/site.css", "inc/layout.php", "inc/parts.php", "templates/home.php",
  "templates/hub.php", "templates/service.php", "templates/guide.php", "templates/zone.php",
  "templates/partner.php", "templates/landing.php", "templates/thanks.php", "templates/contact.php",
  "assets/img/favicon.svg"];
let total = 0;
for (const f of files) {
  if (!fs.existsSync(f)) continue;
  let s = fs.readFileSync(f, "utf8");
  for (const [from, to] of Object.entries(map)) {
    const re = new RegExp(from.replace(/[()]/g, "\\$&"), "gi");
    const n = (s.match(re) || []).length;
    if (n) { s = s.replace(re, to); total += n; }
  }
  fs.writeFileSync(f, s);
}
console.log(`replaced ${total} color values`);
