const fs = require('fs');

let file = 'resources/views/dashboard.blade.php';
let content = fs.readFileSync(file, 'utf8');

// Card 1
content = content.replace(/bg-secondary-container\/20 text-secondary/g, 'bg-blue-500/20 text-blue-400');

// Card 2
content = content.replace(/bg-primary-fixed\/30 text-primary/g, 'bg-orange-500/20 text-orange-400');

// Card 3
content = content.replace(/bg-emerald-500\/20 text-emerald-650/g, 'bg-emerald-500/20 text-emerald-400');

// Card 4
content = content.replace(/bg-red-500\/20 text-red-650/g, 'bg-red-500/20 text-red-400');

fs.writeFileSync(file, content, 'utf8');

console.log('Fixed dashboard icons contrast.');
