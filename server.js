import { createServer } from "http";
import { Server } from "socket.io";
import express from "express";
import cors from "cors";

const app = express();
app.use(cors());

const server = createServer(app);
const io = new Server(server, {
    cors: { origin: "*" }
});

io.on("connection", (socket) => {
    console.log("User connected:", socket.id);

    socket.on("message", (data) => {
            io.emit(`chat:${data.chat_id}`, data);
        console.log("Message:", data);
    });
    socket.on("disconnect", () => {
       console.log("User")
    });
});
server.listen(6001, () => {
    console.log("Socket.IO server running on port http://103.186.20.110:6001");
});
