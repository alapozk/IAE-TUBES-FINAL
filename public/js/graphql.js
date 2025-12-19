/**
 * GraphQL Client for IAE-TUBES
 * Provides easy-to-use methods for GraphQL queries and mutations
 */

const GraphQL = {
    endpoint: '/graphql',

    /**
     * Get CSRF token from meta tag
     */
    getToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    },

    /**
     * Execute a GraphQL query
     * @param {string} query - GraphQL query string
     * @param {object} variables - Query variables
     * @returns {Promise<object>} Response data
     */
    async query(query, variables = {}) {
        try {
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getToken(),
                },
                credentials: 'same-origin',
                body: JSON.stringify({ query, variables })
            });

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
            }

            const result = await response.json();

            if (result.errors) {
                const errorMessage = result.errors.map(e => e.message).join(', ');
                throw new Error(errorMessage);
            }

            return result.data || {};
        } catch (error) {
            console.error('GraphQL query failed:', error);
            throw error;
        }
    },

    /**
     * Execute a GraphQL mutation
     * @param {string} mutation - GraphQL mutation string
     * @param {object} variables - Mutation variables
     * @returns {Promise<object>} Response data
     */
    async mutate(mutation, variables = {}) {
        return this.query(mutation, variables);
    },

    /**
     * Upload file via GraphQL (multipart form)
     * @param {string} mutation - GraphQL mutation string
     * @param {object} variables - Mutation variables (including file)
     * @param {File} file - File object to upload
     * @param {string} fileKey - Variable name for the file
     * @returns {Promise<object>} Response data
     */
    async upload(mutation, variables, file, fileKey = 'file') {
        try {
            const formData = new FormData();

            // Build the operations JSON
            const operations = {
                query: mutation,
                variables: { ...variables, [fileKey]: null }
            };
            formData.append('operations', JSON.stringify(operations));

            // Build the map
            const map = { '0': [`variables.${fileKey}`] };
            formData.append('map', JSON.stringify(map));

            // Append the file
            formData.append('0', file);

            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getToken(),
                },
                credentials: 'same-origin',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
            }

            const result = await response.json();

            if (result.errors) {
                const errorMessage = result.errors.map(e => e.message).join(', ');
                throw new Error(errorMessage);
            }

            return result.data || {};
        } catch (error) {
            console.error('GraphQL upload failed:', error);
            throw error;
        }
    }
};

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GraphQL;
}
