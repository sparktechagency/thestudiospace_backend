import { createServer } from "http";
import { Server } from "socket.io";
import express from "express";
import cors from "cors";
import axios from "axios";

const app = express();
app.use(cors());

const server = createServer(app);
const io = new Server(server, {
  cors: { origin: "*" },
});

// ---------------- CONFIG ----------------
const BASE_API_URL = "http://103.186.20.110:8888/api/update-online-status";
const HEARTBEAT_INTERVAL = 30000; // check every 30s
const TIMEOUT = 60000; // offline after 60s

// ---------------- STORE ----------------
let onlineUsers = {};

// ---------------- API FUNCTION ----------------
const updateUserStatus = async (userId, status) => {
  try {
    await axios.patch(`${BASE_API_URL}/${userId}`, {
      is_online: status,
    });
    console.log(`DB: User ${userId} -> ${status ? "ONLINE" : "OFFLINE"}`);
  } catch (err) {
    console.error("API error:", err.message);
  }
};

// ---------------- SOCKET ----------------
io.on("connection", (socket) => {
  console.log("Connected:", socket.id);

  // USER ONLINE
  socket.on("user_online", (user) => {
    const userId = typeof user === "object" ? user.id : user;
    socket.userId = userId;

    if (!onlineUsers[userId]) {
      onlineUsers[userId] = {
        sockets: new Set(),
        lastActive: Date.now(),
      };
    }

    onlineUsers[userId].sockets.add(socket.id);
    onlineUsers[userId].lastActive = Date.now();

    // First connection
    if (onlineUsers[userId].sockets.size === 1) {
      console.log(`User ${userId} ONLINE`);

      io.emit("user_status_change", {
        userId,
        status: "online",
      });

      updateUserStatus(userId, true);
    }
  });

  // HEARTBEAT
  socket.on("heartbeat", (user) => {
    const userId = typeof user === "object" ? user.id : user;

    if (onlineUsers[userId]) {
      onlineUsers[userId].lastActive = Date.now();
    }
  });

  // DISCONNECT
  socket.on("disconnect", () => {
    const userId = socket.userId;

    if (userId && onlineUsers[userId]) {
      onlineUsers[userId].sockets.delete(socket.id);

      if (onlineUsers[userId].sockets.size === 0) {
        console.log(`User ${userId} disconnected, waiting timeout`);
      }
    }
  });
});

// ---------------- OFFLINE CHECK ----------------
setInterval(() => {
  const now = Date.now();

  for (const [userId, data] of Object.entries(onlineUsers)) {
    if (now - data.lastActive > TIMEOUT) {
      delete onlineUsers[userId];

      console.log(`User ${userId} OFFLINE`);

      io.emit("user_status_change", {
        userId,
        status: "offline",
      });

      updateUserStatus(userId, false);
    }
  }
}, HEARTBEAT_INTERVAL);

// ---------------- START ----------------
server.listen(6001, () => {
  console.log("Server running on port 6001");
});
