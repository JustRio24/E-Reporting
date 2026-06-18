const fs = require('fs');

const file = 'resources/views/reports/index.blade.php';
let content = fs.readFileSync(file, 'utf8');

// The labels were text-slate-400
content = content.replace(/\btext-slate-400\b/g, 'text-blue-200');

fs.writeFileSync(file, content, 'utf8');

console.log('Fixed label text color in reports index.');
