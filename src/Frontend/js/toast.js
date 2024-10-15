const notifications = document.querySelector(".notifications");

const toastDetails = {
  timer: 2000,
  success: {
    icon: "fa-circle-check",
    defaultText: "This is a success toast.",
  },
  error: {
    icon: "fa-circle-xmark",
    defaultText: "This is an error toast.",
  },
  warning: {
    icon: "fa-triangle-exclamation",
    defaultText: "This is a warning toast.",
  },
  info: {
    icon: "fa-circle-info",
    defaultText: "This is an information toast.",
  },
  random: {
    icon: "fa-star",
    defaultText: "This is a random toast.",
  },
};

const removeToast = (toast) => {
  toast.classList.add("hide");
  if (toast.timeoutId) clearTimeout(toast.timeoutId);
  setTimeout(() => toast.remove(), 500);
};

const createToast = (type, message) => {
  const { icon, defaultText } = toastDetails[type];
  const text = message || defaultText;
  const toast = document.createElement("li");
  toast.className = `toast ${type}`;
  toast.innerHTML = `<div class="column">
                         <i class="fa-solid ${icon}"></i>
                         <span>${text}</span>
                      </div>
                      <i class="fa-solid fa-xmark" onclick="removeToast(this.parentElement)"></i>`;
  notifications.appendChild(toast);
  toast.timeoutId = setTimeout(() => removeToast(toast), toastDetails.timer);
};

const showToast = (type, message) => {
  if (toastDetails[type]) {
    createToast(type, message);
  } else {
    console.error(`Toast type "${type}" is not defined.`);
  }
};