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

    // Replace table row hover states
    // Currently they might be `hover:bg-slate-50/50 transition-colors` or `hover:bg-gray-50 transition-colors`
    content = content.replace(/hover:bg-slate-50\/50\s+transition-colors/g, 'hover:bg-slate-700/30 transition-all duration-200');
    content = content.replace(/hover:bg-gray-50\s+transition-colors/g, 'hover:bg-slate-700/30 transition-all duration-200');
    content = content.replace(/hover:bg-slate-100\s+transition-colors/g, 'hover:bg-slate-700/30 transition-all duration-200');

    // Replace card borders to add transition and hover
    // Currently `border border-slate-700/50`
    // Avoid double replacing if it already has transition-all
    content = content.replace(/border border-slate-700\/50(?! transition-all)/g, 'border border-slate-700/50 transition-all duration-200 hover:border-slate-600/80');

    // And make sure all bg-slate-800/60 backdrop-blur-md have the transitions if they are cards
    // The previous regex catches most cards since they all have borders.

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        count++;
    }
}

console.log(`Updated ${count} files with interactive transitions.`);
