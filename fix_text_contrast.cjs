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

    // 1. Fix Backgrounds and Borders of cards that are still white
    content = content.replace(/\bbg-white\b/g, 'bg-slate-800/60 backdrop-blur-md');
    content = content.replace(/\bborder-slate-200\b/g, 'border-slate-700/50');
    content = content.replace(/\bborder-slate-205\b/g, 'border-slate-700/50');
    content = content.replace(/\bborder-slate-300\b/g, 'border-slate-600/50');
    
    // 2. Fix text colors for dark background
    content = content.replace(/\btext-slate-900\b/g, 'text-white');
    content = content.replace(/\btext-gray-900\b/g, 'text-white');
    content = content.replace(/\btext-slate-800\b/g, 'text-slate-200');
    content = content.replace(/\btext-gray-800\b/g, 'text-slate-200');
    content = content.replace(/\btext-slate-700\b/g, 'text-slate-300');
    content = content.replace(/\btext-gray-700\b/g, 'text-slate-300');
    content = content.replace(/\btext-slate-600\b/g, 'text-slate-300');
    content = content.replace(/\btext-gray-600\b/g, 'text-slate-300');
    content = content.replace(/\btext-slate-500\b/g, 'text-slate-400');
    content = content.replace(/\btext-gray-500\b/g, 'text-slate-400');
    content = content.replace(/\btext-black\b/g, 'text-white');

    // 3. Fix table hover and divides
    content = content.replace(/\bhover:bg-slate-50\/50\b/g, 'hover:bg-slate-700/30');
    content = content.replace(/\bhover:bg-slate-50\b/g, 'hover:bg-slate-700/30');
    content = content.replace(/\bdivide-slate-200\b/g, 'divide-slate-700/50');

    // 4. Fix specific hardcoded texts in dashboard map tooltip/icons
    content = content.replace(/\btext-slate-700\/40\b/g, 'text-slate-300/60');
    
    // 5. Fix form input borders and backgrounds so they are visible
    // Sometimes inputs have bg-white, which we replaced with bg-slate-800/60. Let's make sure inputs have correct text.
    // Inputs usually have text-slate-900 which is now text-white. That's good.

    // 6. Fix badge colors (blue, emerald, amber, purple/fuchsia, red) that are light mode
    // Blue
    content = content.replace(/\bbg-blue-50\b/g, 'bg-blue-500/10');
    content = content.replace(/\btext-blue-700\b/g, 'text-blue-300');
    content = content.replace(/\bborder-blue-200\b/g, 'border-blue-500/30');
    // Emerald
    content = content.replace(/\bbg-emerald-50\b/g, 'bg-emerald-500/10');
    content = content.replace(/\bbg-emerald-100\b/g, 'bg-emerald-500/20');
    content = content.replace(/\btext-emerald-700\b/g, 'text-emerald-300');
    content = content.replace(/\btext-emerald-800\b/g, 'text-emerald-400');
    content = content.replace(/\bborder-emerald-200\b/g, 'border-emerald-500/30');
    // Amber / Yellow
    content = content.replace(/\bbg-amber-50\b/g, 'bg-yellow-500/10');
    content = content.replace(/\btext-amber-700\b/g, 'text-yellow-300');
    content = content.replace(/\bborder-amber-200\b/g, 'border-yellow-500/30');
    // Purple -> Fuchsia
    content = content.replace(/\bbg-purple-50\b/g, 'bg-fuchsia-500/10');
    content = content.replace(/\btext-purple-700\b/g, 'text-fuchsia-300');
    content = content.replace(/\bborder-purple-200\b/g, 'border-fuchsia-500/30');
    // Red
    content = content.replace(/\bbg-red-50\b/g, 'bg-red-500/10');
    content = content.replace(/\bbg-red-100\b/g, 'bg-red-500/20');
    content = content.replace(/\btext-red-700\b/g, 'text-red-300');
    content = content.replace(/\btext-red-800\b/g, 'text-red-400');
    content = content.replace(/\bborder-red-200\b/g, 'border-red-500/30');
    // Slate
    content = content.replace(/\bbg-slate-100\b/g, 'bg-slate-700/50');

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        count++;
    }
}

console.log(`Fixed text contrast and background global variables in ${count} files.`);
