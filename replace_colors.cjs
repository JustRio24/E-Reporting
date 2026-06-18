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

const replacements = {
    // Phase 1 (if missed) + Phase 2 (gradient, blur)
    // Backgrounds
    'bg-slate-50': 'bg-gradient-to-br from-slate-900 via-slate-950 to-zinc-900',
    'bg-gray-50': 'bg-gradient-to-br from-slate-900 via-slate-950 to-zinc-900',
    'bg-slate-900': 'bg-gradient-to-br from-slate-900 via-slate-950 to-zinc-900', // Catch anything I replaced earlier
    'bg-white': 'bg-slate-800/60 backdrop-blur-md',
    'bg-slate-800': 'bg-slate-800/60 backdrop-blur-md', // Catch anything I replaced earlier
    
    // Texts
    'text-slate-900': 'text-white',
    'text-gray-900': 'text-white',
    'text-slate-800': 'text-white',
    'text-gray-800': 'text-white',
    'text-slate-50': 'text-white', // Catch anything I replaced earlier
    'text-slate-500': 'text-slate-300',
    'text-gray-500': 'text-slate-300',
    'text-slate-600': 'text-slate-300',
    'text-gray-600': 'text-slate-300',
    'text-slate-400': 'text-slate-300', // Catch anything I replaced earlier
    
    // Borders
    'border-slate-200': 'border-slate-700/50',
    'border-gray-200': 'border-slate-700/50',
    'border-slate-700': 'border-slate-700/50', // Catch anything I replaced earlier
};

let count = 0;

for (const file of files) {
    let content = fs.readFileSync(file, 'utf8');
    let original = content;

    for (const [oldClass, newClass] of Object.entries(replacements)) {
        // Use regex to match exactly the class name, avoiding partial matches.
        // Also avoid double replacing (e.g. if newClass contains oldClass)
        // Wait, 'bg-slate-900' is IN 'bg-gradient-to-br from-slate-900 ...'
        // So we need a lookbehind and lookahead to ensure we don't match inside a gradient class.
        
        const regex = new RegExp(`(?<!from-|via-|to-)\\b${oldClass}\\b(?!/60|/50)`, 'g');
        content = content.replace(regex, newClass);
    }

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        count++;
    }
}

console.log(`Updated ${count} files.`);
