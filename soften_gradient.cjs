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

    const oldGradient1 = 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-500/40 backdrop-blur-md';
    const oldGradient2 = 'bg-gradient-to-br from-slate-900/80 via-blue-900/50 to-yellow-700/20 backdrop-blur-md';
    
    // Softer yellow gradient
    const newGradient = 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md';

    content = content.replace(new RegExp(oldGradient1.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), newGradient);
    content = content.replace(new RegExp(oldGradient2.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), newGradient);

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        count++;
    }
}

console.log(`Softened yellow gradient on cards in ${count} files.`);
