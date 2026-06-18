const fs = require('fs');
const file = 'resources/views/layouts/app.blade.php';
let content = fs.readFileSync(file, 'utf8');

// Fix the active link condition block
const searchStr = "? 'bg-slate-800/60 backdrop-blur-md/10 text-white border-l-4 ' . $roleAccentBorder : ''";
const replaceStr = "? 'bg-slate-800/60 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : 'text-slate-300 hover:text-white'";

// Actually wait, the `a` tag already has `text-slate-300 hover:text-white` or similar?
// In the current file: 
// class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-slate-800/60 backdrop-blur-md hover:text-white transition-colors duration-150 {{ Request::is('dashboard*') ? 'bg-slate-800/60 backdrop-blur-md/10 text-white border-l-4 ' . $roleAccentBorder : '' }}"
// We need to clean up `hover:bg-slate-800/60 backdrop-blur-md` because hover: doesn't apply to the second class!
// It should be `hover:bg-slate-800/60 hover:backdrop-blur-md`.

// Let's replace the whole `a` class string for each link using a regex!
// The structure is roughly:
// class="flex items-center ... hover:bg-slate-800/60 backdrop-blur-md hover:text-white ... {{ Request::is(...) ? ... : '' }}"

content = content.replace(/class="([^"]*?hover:bg-slate-800\/60 backdrop-blur-md hover:text-white[^"]*?\{\{ Request::is\([^)]+\) \? 'bg-slate-800\/60 backdrop-blur-md\/10 text-white border-l-4 ' \. \$roleAccentBorder : '' \}\})"/g, (match) => {
    // Clean up the match
    let newMatch = match.replace('hover:bg-slate-800/60 backdrop-blur-md', 'hover:bg-slate-800/60 hover:backdrop-blur-md');
    newMatch = newMatch.replace("? 'bg-slate-800/60 backdrop-blur-md/10 text-white border-l-4 ' . $roleAccentBorder : ''", "? 'bg-slate-800/60 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : ''");
    return newMatch;
});

// Also fix the role badge:
// Current: <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-bold tracking-wider uppercase font-mono bg-slate-800/60 backdrop-blur-md {{ $roleAccentText }} border border-slate-700/50">
const roleBadgeSearch = 'bg-slate-800/60 backdrop-blur-md {{ $roleAccentText }} border border-slate-700/50';
const roleBadgeReplace = 'bg-slate-800/60 backdrop-blur-md shadow-lg {{ $roleAccentText }} {{ $roleAccentShadow }} border border-slate-700/50';
content = content.replace(new RegExp(roleBadgeSearch.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), roleBadgeReplace);

// Also fix profile dropdown which has `bg-slate-800/60 backdrop-blur-md border border-slate-700/50` -> needs to be just clean
// It's already fine.

fs.writeFileSync(file, content, 'utf8');
console.log('app.blade.php updated');
