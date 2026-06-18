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

    const harshString = ' border-t border-t-yellow-500/30 transition-all duration-500 ease-in-out hover:border-yellow-500/40 hover:shadow-md hover:shadow-yellow-500/10';
    const smoothString = ' transition-all duration-500 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5';

    if (content.includes(harshString)) {
        content = content.replace(new RegExp(harshString.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), smoothString);
    }

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        count++;
    }
}

console.log(`Smoothed card accents in ${count} files.`);
