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

    // Fix glaring light headers in cards
    content = content.replace(/\bbg-slate-50\b/g, 'bg-slate-900/40');
    
    // Fix specific hardcoded text colors in card headers
    content = content.replace(/\btext-slate-805\b/g, 'text-yellow-400');
    content = content.replace(/<h3([^>]*)text-slate-800([^>]*)>/g, '<h3$1text-yellow-400$2>');
    content = content.replace(/<h3([^>]*)text-slate-900([^>]*)>/g, '<h3$1text-yellow-400$2>');
    content = content.replace(/<h3([^>]*)text-slate-200([^>]*)>/g, '<h3$1text-yellow-400$2>');
    
    // Fix text-slate-650 which is commonly used for labels
    content = content.replace(/\btext-slate-650\b/g, 'text-blue-200');
    
    // In reports index, fix the empty state text
    content = content.replace(/text-center text-slate-400 font-mono/g, 'text-center text-yellow-200/80 font-mono');

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        count++;
    }
}

console.log(`Updated card titles and text contrast in ${count} files.`);
