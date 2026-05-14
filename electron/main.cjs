const { app, BrowserWindow, session } = require('electron');

const path = require('path');

function createWindow() {

    const win = new BrowserWindow({
        width: 1400,
        height: 900
    });

    win.loadURL('https://portal.nyumbaiitutv.co.ke');

    /*
    |--------------------------------------------------------------------------
    | AUTO DOWNLOADS
    |--------------------------------------------------------------------------
    */

    session.defaultSession.on(
        'will-download',
        (event, item) => {

            const downloadsPath = path.join(
                app.getPath('downloads'),
                item.getFilename()
            );

            item.setSavePath(downloadsPath);

            console.log(
                'Downloading:',
                downloadsPath
            );
        }
    );
}

app.whenReady().then(createWindow);
