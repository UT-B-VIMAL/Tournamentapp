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

        // ✅ Handle new wrapper format
        if (response.data?.result?.body) {
            const parsedBody = JSON.parse(response.data.result.body);

            // Return in the OLD format your integration expects
            return {
                code: parsedBody.code,
                message: parsedBody.message,
                data: JSON.stringify(parsedBody.data) // keep as string
            };
        }

        // ✅ Old API already in correct format
        return response.data;

    } catch (error) {
        console.error("API Error:", error.response?.data || error.message);
        throw error;
    }
}



