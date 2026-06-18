const fs = require('fs');
const path = require('path');

const walkSync = (dir, filelist = []) => {
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const dirFile = path.join(dir, file);
        if (fs.statSync(dirFile).isDirectory()) {
            filelist = walkSync(dirFile, filelist);
        } else if (dirFile.endsWith('.blade.php')) {
            filelist.push(dirFile);
        }
    }
    return filelist;
};

const viewsDir = path.join(__dirname, 'resources', 'views');
const files = walkSync(viewsDir);

let count = 0;

for (const file of files) {
    let content = fs.readFileSync(file, 'utf8');
    let original = content;

    const newGradient = 'bg-gradient-to-br from-slate-900/80 via-blue-900/50 to-yellow-700/20 backdrop-blur-md';

    // Replace the global cards
    content = content.replace(/bg-slate-800\/60 backdrop-blur-md/g, newGradient);
    
    // Replace the dashboard KPI cards
    content = content.replace(/bg-secondary-dark\/30 backdrop-blur-md/g, newGradient);

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        count++;
    }
}

console.log(`Applied navy-yellow gradient to cards in ${count} files.`);
