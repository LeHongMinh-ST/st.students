import "./bootstrap";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

Noty.overrideDefaults({
    theme: "limitless",
    layout: "topRight",
    type: "alert",
    timeout: 2500,
});

window.addEventListener("alert", (event) => {
    new Noty({
        title: event.detail.title ?? "",
        text: event.detail.message,
        type: event.detail.type ?? "alert",
    }).show();
});

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

window.Echo.connector.pusher.connection.bind("connected", () => {
    console.log("connected");
});

window.Echo.channel("test-event").listenToAll((event, data) => {
    // do what you need to do based on the event name and data
    console.log(event, data);
});
