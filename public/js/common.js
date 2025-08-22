// public/js/common.js

// Read API base URL from meta tag in Blade layout
const API_BASE_URL = document.head.querySelector('meta[name="api-base-url"]').content;

// Make function globally available
async function apiRequest(endpoint, method = "GET", data = {}, headers = {}) {
    try {
                

        const response = await axios({
            url: `${API_BASE_URL}/${endpoint}`,
            method: method,
            data: method !== "GET" ? data : {},
            params: method === "GET" ? data : {},
            headers: {
                "Content-Type": "application/json",
                ...headers
            }
        });
alert(response);
        return response.data;
    } catch (error) {
        console.error("API Error:", error.response?.data || error.message);
        throw error;
    }
}
