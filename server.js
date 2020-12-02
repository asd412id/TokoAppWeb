const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const path = require('path');

const app = express();
app.use(express.static(path.join(__dirname, 'public')));

const server = http.Server(app);
server.listen(23393);

const io = socketIo(server,{
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
    credentials: true
  }
});

app.post('/', function (req, res) {
  io.emit('kode',req.get('kode'));
  console.log('Kode: '+req.get('kode'));
  res.json({
    'status': 'success',
    'kode': req.get('kode'),
  })
})
