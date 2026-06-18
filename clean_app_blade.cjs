const fs = require('fs');

let file = 'resources/views/layouts/app.blade.php';
let content = fs.readFileSync(file, 'utf8');

const harshString = ' border-t border-t-yellow-500/30 transition-all duration-500 ease-in-out hover:border-yellow-500/40 hover:shadow-md hover:shadow-yellow-500/10';

content = content.replace(new RegExp(harshString.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), '');

fs.writeFileSync(file, content, 'utf8');

console.log('Cleaned up app.blade.php');
