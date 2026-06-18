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

    // Search for the standard card classes we set earlier
    const searchString = 'border border-slate-700/50 transition-all duration-200 hover:border-slate-600/80';
    const replaceString = 'border border-slate-700/50 border-t-2 border-t-yellow-500/70 transition-all duration-300 hover:border-yellow-500/50 hover:shadow-lg hover:shadow-yellow-500/10';

    if (content.includes(searchString)) {
        content = content.replace(new RegExp(searchString.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), replaceString);
    }
    
    // Also, some cards might just have 'border border-slate-700/50' if they didn't get the hover
    // but the previous script should have caught them all.
    // Let's also do a fallback just in case:
    const searchStringFallback = 'border border-slate-700/50 ';
    // Wait, let's not risk double replacing. The previous script caught exactly `border border-slate-700/50(?! transition-all)` 
    // and replaced with the `searchString` above. So all cards should have it.

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        count++;
    }
}

console.log(`Added yellow accents to cards in ${count} files.`);
