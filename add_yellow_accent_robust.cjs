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

    // We want to add yellow top border and yellow hover glow to any card.
    // A card is typically identified by having "rounded", "shadow", and "border".
    // We will inject the new classes right before the closing quote of the class attribute.
    
    // Pattern: class="... rounded-lg ... shadow-sm ... border ... "
    // Note: We need to avoid adding it multiple times.
    const yellowClasses = ' border-t-2 border-t-yellow-500/80 transition-all duration-300 hover:border-yellow-400 hover:shadow-lg hover:shadow-yellow-500/20';

    content = content.replace(/class="([^"]*rounded(?:-[a-z]+)?\s+[^"]*shadow(?:-[a-z]+)?\s+[^"]*border[^"]*)"/g, (match, p1) => {
        if (!p1.includes('border-t-yellow-500')) {
            return `class="${p1}${yellowClasses}"`;
        }
        return match;
    });

    // Also let's check for `bg-white rounded-lg border` which might not have shadow in some cases
    content = content.replace(/class="([^"]*bg-white\s+rounded-lg\s+border[^"]*)"/g, (match, p1) => {
        if (!p1.includes('border-t-yellow-500') && !p1.includes('shadow')) {
            return `class="${p1}${yellowClasses}"`;
        }
        return match;
    });

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        count++;
    }
}

console.log(`Added yellow accents to cards in ${count} files.`);
