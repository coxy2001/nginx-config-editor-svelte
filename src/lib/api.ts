import { AUTH, API_URL } from "./config.js";

function getOptions() {
    let headers = new Headers();
    headers.append("Authorization", AUTH);

    return {
        method: "GET",
        headers: headers,
    };
}

export function getFile(filename, callback) {
    fetch(`${API_URL}/file/${filename}`, getOptions())
        .then((response) => response.text())
        .then(callback);
}

export function saveFile(filename, text) {
    let options = getOptions();
    options.method = "POST";
    options.headers.append("Content-Type", "text/plain");
    options["body"] = text;

    fetch(`${API_URL}/file/${filename}`, options);
}

export function deleteFile(filename) {
    let options = getOptions();
    options.method = "DELETE";

    fetch(`${API_URL}/file/${filename}`, options);
}

export function enableFile(filename) {
    fetch(`${API_URL}/enable/${filename}`, getOptions());
}

export function disableFile(filename) {
    fetch(`${API_URL}/disable/${filename}`, getOptions());
}

export function serverRestart(callback) {
    fetch(`${API_URL}/restart`, getOptions())
        .then((response) => response.json())
        .then(callback);
}

export function getSitesAvailable(callback) {
    fetch(`${API_URL}/available`, getOptions())
        .then((response) => response.json())
        .then(callback);
}

export function getSitesEnabled(callback) {
    fetch(`${API_URL}/enabled`, getOptions())
        .then((response) => response.json())
        .then(callback);
}
