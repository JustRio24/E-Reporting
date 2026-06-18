const { Jimp, intToRGBA } = require('jimp');

async function removeBackground() {
    try {
        const image = await Jimp.read('C:\\Users\\user\\Downloads\\logo ptba.jpg');
        
        const width = image.bitmap.width;
        const height = image.bitmap.height;
        
        // Define tolerance
        const tolerance = 40;
        
        // Get the background color from top-left pixel
        const bgColorInt = image.getPixelColor(0, 0);
        const bgRgba = intToRGBA(bgColorInt);
        
        console.log(`Background color detected as: r=${bgRgba.r}, g=${bgRgba.g}, b=${bgRgba.b}`);
        
        image.scan(0, 0, width, height, function(x, y, idx) {
            const r = this.bitmap.data[idx + 0];
            const g = this.bitmap.data[idx + 1];
            const b = this.bitmap.data[idx + 2];
            
            // Background is a very light gray/blue, but to be safe, check distance to top-left pixel
            if (
                Math.abs(r - bgRgba.r) <= tolerance &&
                Math.abs(g - bgRgba.g) <= tolerance &&
                Math.abs(b - bgRgba.b) <= tolerance
            ) {
                this.bitmap.data[idx + 3] = 0; // alpha to 0
            }
        });
        
        image.write('C:\\Users\\user\\OneDrive\\Desktop\\E-Reporting\\public\\images\\logo.png');
        console.log('Background removed and saved to public/images/logo.png!');
    } catch (e) {
        console.error(e);
    }
}

removeBackground();
