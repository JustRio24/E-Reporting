const fs = require('fs');

// 1. Update app.blade.php
let appFile = 'resources/views/layouts/app.blade.php';
let appContent = fs.readFileSync(appFile, 'utf8');

appContent = appContent.replace(
    /\$roleAccentBorder = 'border-fuchsia-600';\s*\$roleAccentText = 'text-fuchsia-400';\s*\$roleAccentHoverText = 'hover:text-fuchsia-300';\s*\$roleAccentShadow = 'shadow-fuchsia-500\/20';\s*\$roleBadgeBg = 'bg-fuchsia-500\/10';\s*\$roleBadgeText = 'text-fuchsia-300';\s*\$roleBadgeBorder = 'border-fuchsia-500\/30';/,
    `$roleAccentBorder = 'border-yellow-500';
                    $roleAccentText = 'text-yellow-400';
                    $roleAccentHoverText = 'hover:text-yellow-300';
                    $roleAccentShadow = 'shadow-yellow-500/20';
                    $roleBadgeBg = 'bg-yellow-500/10';
                    $roleBadgeText = 'text-yellow-300';
                    $roleBadgeBorder = 'border-yellow-500/30';`
);

fs.writeFileSync(appFile, appContent, 'utf8');

// 2. Update primary-button.blade.php
let btnFile = 'resources/views/components/primary-button.blade.php';
let btnContent = fs.readFileSync(btnFile, 'utf8');

btnContent = btnContent.replace(
    /'bg-fuchsia-600 text-white hover:bg-fuchsia-500 shadow-lg shadow-fuchsia-500\/20 focus:ring-fuchsia-500'/,
    `'bg-yellow-500 text-slate-900 hover:bg-yellow-400 shadow-lg shadow-yellow-500/20 focus:ring-yellow-500'`
);

fs.writeFileSync(btnFile, btnContent, 'utf8');

console.log('Replaced fuchsia with yellow for Admin role.');
