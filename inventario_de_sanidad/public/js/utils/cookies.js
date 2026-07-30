export function setCookieValue(data, cookieName, duration = 2 * 24 * 60 * 60 * 1000) {
    let dateExpiration = new Date();

    dateExpiration.setTime(dateExpiration.getTime() + duration);
    let expiration = dateExpiration.toUTCString();

    let value = encodeURIComponent(JSON.stringify(data));
    if (value.length > 4096) {
        return false;
    }

    document.cookie = cookieName + "=" + value + "; expires=" + expiration + "; path=/";

    return true;
}

export function getCookieValue(cookieName) {
    const cookies = document.cookie.split(";");

    for (const cookie of cookies) {
        const trimmedCookie = cookie.trim();

        if (trimmedCookie.startsWith(cookieName + "=")) {
            try {
                return JSON.parse(
                    decodeURIComponent(trimmedCookie.substring(cookieName.length + 1))
                );
            } catch (error) {
                console.error("Error al parsear la cookie:", error);
                return [];
            }
        }
    }

    return [];
}

// Borra una cookie estableciendo una fecha de expiración en el pasado.
export function deleteCookie(cookieName) {
    document.cookie = cookieName + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
}