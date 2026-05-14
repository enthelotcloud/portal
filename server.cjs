const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const multer = require('multer');
const cors = require('cors');
const path = require('path');
const fs = require('fs');

const app = express();

app.use(cors());

const server = http.createServer(app);

const io = new Server(server, {
    cors: {
        origin: '*'
    }
});

/*
|--------------------------------------------------------------------------
| UPLOAD DIRECTORY
|--------------------------------------------------------------------------
*/

const uploadPath = path.join(__dirname, 'uploads');

if (!fs.existsSync(uploadPath)) {

    fs.mkdirSync(uploadPath);
}

/*
|--------------------------------------------------------------------------
| MULTER STORAGE
|--------------------------------------------------------------------------
*/

const storage = multer.diskStorage({

    destination: function (req, file, cb) {

        cb(null, uploadPath);
    },

    filename: function (req, file, cb) {

        cb(
            null,
            Date.now() + '-' + file.originalname
        );
    }
});

const upload = multer({ storage });

/*
|--------------------------------------------------------------------------
| USERS
|--------------------------------------------------------------------------
*/

let users = {};

/*
|--------------------------------------------------------------------------
| SOCKET CONNECTION
|--------------------------------------------------------------------------
*/

io.on('connection', (socket) => {

    console.log('Connected:', socket.id);

    /*
    |--------------------------------------------------------------------------
    | REGISTER DEVICE
    |--------------------------------------------------------------------------
    */

    socket.on('register', (name) => {

        users[name] = socket.id;

        io.emit(
            'users',
            Object.keys(users)
        );

        console.log('Registered:', name);
    });

    /*
    |--------------------------------------------------------------------------
    | WEBRTC OFFER
    |--------------------------------------------------------------------------
    */

    socket.on('webrtc-offer', (data) => {

        const targetSocket =
            users[data.target];

        if (!targetSocket) return;

        io.to(targetSocket).emit(
            'webrtc-offer',
            {
                signal: data.signal,
                from: socket.id
            }
        );
    });

    /*
    |--------------------------------------------------------------------------
    | WEBRTC ANSWER
    |--------------------------------------------------------------------------
    */

    socket.on('webrtc-answer', (data) => {

        io.to(data.target).emit(
            'webrtc-answer',
            {
                signal: data.signal
            }
        );
    });

    /*
    |--------------------------------------------------------------------------
    | FALLBACK TRANSFER
    |--------------------------------------------------------------------------
    */

    socket.on('send-transfer', (data) => {

        const receiverSocket =
            users[data.to];

        if (!receiverSocket) return;

        io.to(receiverSocket).emit(
            'incoming-transfer',
            {
                from: data.from,
                fileName: data.fileName,
                downloadUrl: data.downloadUrl
            }
        );
    });

    /*
    |--------------------------------------------------------------------------
    | DISCONNECT
    |--------------------------------------------------------------------------
    */

    socket.on('disconnect', () => {

        for (let name in users) {

            if (users[name] === socket.id) {

                delete users[name];
            }
        }

        io.emit(
            'users',
            Object.keys(users)
        );

        console.log(
            'Disconnected:',
            socket.id
        );
    });
});

/*
|--------------------------------------------------------------------------
| FILE UPLOAD
|--------------------------------------------------------------------------
*/

app.post(
    '/upload',
    upload.single('file'),
    (req, res) => {

        if (!req.file) {

            return res.status(400).json({
                success: false
            });
        }

        const fileUrl =
            `${req.protocol}://${req.get('host')}/uploads/${req.file.filename}`;

        res.json({
            success: true,
            fileName: req.file.originalname,
            downloadUrl: fileUrl
        });
    }
);

/*
|--------------------------------------------------------------------------
| STATIC FILES
|--------------------------------------------------------------------------
*/

app.use(
    '/uploads',
    express.static(uploadPath)
);

/*
|--------------------------------------------------------------------------
| AUTO DELETE FILES AFTER 6 HOURS
|--------------------------------------------------------------------------
*/

setInterval(() => {

    fs.readdir(
        uploadPath,
        (err, files) => {

            if (err) return;

            files.forEach(file => {

                const filePath =
                    path.join(
                        uploadPath,
                        file
                    );

                fs.stat(
                    filePath,
                    (err, stat) => {

                        if (err) return;

                        const now =
                            Date.now();

                        const fileAge =
                            now - stat.mtimeMs;

                        const sixHours =
                            6 *
                            60 *
                            60 *
                            1000;

                        if (
                            fileAge >
                            sixHours
                        ) {

                            fs.unlink(
                                filePath,
                                () => {

                                    console.log(
                                        'Deleted:',
                                        file
                                    );
                                }
                            );
                        }
                    }
                );
            });
        }
    );

}, 60 * 60 * 1000);

/*
|--------------------------------------------------------------------------
| START SERVER
|--------------------------------------------------------------------------
*/

server.listen(3001, '0.0.0.0', () => {

    console.log(
        'Server running on port 3001'
    );
});
