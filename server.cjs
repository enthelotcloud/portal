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
        origin: "*"
    }
});

const uploadPath = path.join(__dirname, 'uploads');

if (!fs.existsSync(uploadPath)) {
    fs.mkdirSync(uploadPath);
}

const storage = multer.diskStorage({
    destination: function (req, file, cb) {
        cb(null, uploadPath);
    },

    filename: function (req, file, cb) {
        cb(null, Date.now() + '-' + file.originalname);
    }
});

const upload = multer({ storage });

let users = {};

io.on('connection', (socket) => {

    console.log('Connected:', socket.id);

    socket.on('register', (name) => {

        users[name] = socket.id;

        io.emit('online-users', Object.keys(users));
    });

    socket.on('send-transfer', (data) => {

        const receiverSocket = users[data.to];

        if (receiverSocket) {

            io.to(receiverSocket).emit('incoming-transfer', {
                from: data.from,
                fileName: data.fileName,
                downloadUrl: data.downloadUrl
            });
        }
    });

    socket.on('disconnect', () => {

        for (let name in users) {
            if (users[name] === socket.id) {
                delete users[name];
            }
        }

        io.emit('online-users', Object.keys(users));
    });
});

app.post('/upload', upload.single('file'), (req, res) => {

    const fileUrl = `http://localhost:3001/uploads/${req.file.filename}`;

    res.json({
        success: true,
        fileName: req.file.originalname,
        downloadUrl: fileUrl
    });
});

app.use('/uploads', express.static(uploadPath));

server.listen(3001, () => {
    console.log('Server running on port 3001');
});
