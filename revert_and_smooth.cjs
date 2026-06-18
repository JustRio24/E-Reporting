const fs = require('fs');

// 1. Revert app.blade.php
let appFile = 'resources/views/layouts/app.blade.php';
let appContent = fs.readFileSync(appFile, 'utf8');

appContent = appContent.replace(
    /\$roleAccentBorder = 'border-yellow-500';\s*\$roleAccentText = 'text-yellow-400';\s*\$roleAccentHoverText = 'hover:text-yellow-300';\s*\$roleAccentShadow = 'shadow-yellow-500\/20';\s*\$roleBadgeBg = 'bg-yellow-500\/10';\s*\$roleBadgeText = 'text-yellow-300';\s*\$roleBadgeBorder = 'border-yellow-500\/30';/,
    `$roleAccentBorder = 'border-fuchsia-600';
                    $roleAccentText = 'text-fuchsia-400';
                    $roleAccentHoverText = 'hover:text-fuchsia-300';
                    $roleAccentShadow = 'shadow-fuchsia-500/20';
                    $roleBadgeBg = 'bg-fuchsia-500/10';
                    $roleBadgeText = 'text-fuchsia-300';
                    $roleBadgeBorder = 'border-fuchsia-500/30';`
);

fs.writeFileSync(appFile, appContent, 'utf8');

// 2. Revert primary-button.blade.php
let btnFile = 'resources/views/components/primary-button.blade.php';
let btnContent = fs.readFileSync(btnFile, 'utf8');

btnContent = btnContent.replace(
    /'bg-yellow-500 text-slate-900 hover:bg-yellow-400 shadow-lg shadow-yellow-500\/20 focus:ring-yellow-500'/,
    `'bg-fuchsia-600 text-white hover:bg-fuchsia-500 shadow-lg shadow-fuchsia-500/20 focus:ring-fuchsia-500'`
);

fs.writeFileSync(btnFile, btnContent, 'utf8');

// 3. Make card accents smoother globally
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

    // Search and replace the harsh yellow accent with a smoother, subtle one
    const harshYellow = ' border-t-2 border-t-yellow-500/80 transition-all duration-300 hover:border-yellow-400 hover:shadow-lg hover:shadow-yellow-500/20';
    const smoothYellow = ' border-t border-t-yellow-500/30 transition-all duration-500 hover:border-yellow-500/50 hover:shadow-md hover:shadow-yellow-500/10';

    if (content.includes(harshYellow)) {
        content = content.replace(new RegExp(harshYellow.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), smoothYellow);
    }

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        count++;
    }
}

console.log(`Reverted Admin role to Fuchsia and smoothed card accents in ${count} files.`);
