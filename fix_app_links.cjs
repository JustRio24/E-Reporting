const fs = require('fs');
const file = 'resources/views/layouts/app.blade.php';
let content = fs.readFileSync(file, 'utf8');

// Replace `hover:text-white` with `{{ $roleAccentHoverText }}` in the sidebar nav links.
content = content.replace(/hover:text-white/g, '{{ $roleAccentHoverText }}');

// Replace the icon `group-hover:text-white` with `group-hover:{{ $roleAccentHoverText }}`
// actually wait, there is no `group-hover:{{ $roleAccentHoverText }}` because {{ }} inside class might not work with group-hover if it evaluates to `hover:text-blue-300`!
// If it evaluates to `hover:text-blue-300`, then we shouldn't use `group-hover:`. 
// So let's just make the icon color match the text. If the <a> tag gets `hover:text-blue-300`, the icon can just have no text color class, or use `text-current` so it inherits the text color!
// Or we just remove `text-slate-300 group-hover:text-white` and `text-slate-300` from the icons inside the <a> tag, and let them inherit `text-current`.
// Currently the links have `text-slate-300` so the icon would inherit it anyway.

content = content.replace(/text-slate-300 group-hover:\{\{ \$roleAccentHoverText \}\}/g, 'text-current transition-colors duration-300');
content = content.replace(/text-slate-300/g, (match, offset, str) => {
    // Only replace inside the SVG class
    if (str.substring(offset - 20, offset).includes('<svg class="')) {
        return 'text-current transition-colors duration-300';
    }
    return match;
});

// Also remove `transition-colors duration-150` and replace with `transition-all duration-300`
content = content.replace(/transition-colors duration-150/g, 'transition-all duration-300');

fs.writeFileSync(file, content, 'utf8');
console.log('Fixed link hover colors and transitions');
