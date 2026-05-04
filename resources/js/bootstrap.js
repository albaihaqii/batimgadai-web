import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

if (import.meta.env.VITE_PUSHER_APP_KEY) {
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: "pusher",
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname,
        wsPort: import.meta.env.VITE_PUSHER_PORT || 6001,
        forceTLS: import.meta.env.VITE_PUSHER_APP_USE_TLS === "true",
        enabledTransports: ["ws", "wss"],
        auth: {
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content"),
            },
        },
    });

    window.Echo.private("role.admin").listen("GadaiCreated", (event) => {
        window.dispatchEvent(
            new CustomEvent("gadai-created", { detail: event }),
        );
    });

    window.Echo.private("role.superadmin").listen("GadaiCreated", (event) => {
        window.dispatchEvent(
            new CustomEvent("gadai-created", { detail: event }),
        );
    });

    window.addEventListener("gadai-created", (event) => {
        const toast = document.getElementById("live-toast");
        const message = document.getElementById("live-toast-message");

        if (toast && message) {
            message.textContent = `Pengajuan gadai baru dari ${event.detail.nasabah} di ${event.detail.cabang}.`;
            toast.classList.remove("hidden");

            setTimeout(() => {
                toast.classList.add("hidden");
            }, 7000);
        }

        const path = window.location.pathname;
        if (path.includes("/approval/gadai")) {
            window.location.reload();
        }
    });
}
