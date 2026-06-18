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

const yellowClasses = ' border-t-2 border-t-yellow-500/80 transition-all duration-300 hover:border-yellow-400 hover:shadow-lg hover:shadow-yellow-500/20';

for (const file of files) {
    let content = fs.readFileSync(file, 'utf8');
    let original = content;

    content = content.replace(/class="([^"]*)"/g, (match, classStr) => {
        // Condition for a card: has rounded, border, and either shadow or bg-white
        if (classStr.includes('rounded') && classStr.includes('border') && (classStr.includes('shadow') || classStr.includes('bg-white') || classStr.includes('bg-slate-800') || classStr.includes('bg-secondary-dark'))) {
            // Check if it already has the yellow class to avoid duplicates
            if (!classStr.includes('border-t-yellow-500')) {
                // Ignore small badges or buttons
                if (!classStr.includes('px-2 py-0.5') && !classStr.includes('inline-flex') && !classStr.includes('btn')) {
                    return `class="${classStr}${yellowClasses}"`;
                }
            }
        }
        return match;
    });

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        count++;
    }
}

console.log(`Added yellow accents to cards in ${count} files.`);
