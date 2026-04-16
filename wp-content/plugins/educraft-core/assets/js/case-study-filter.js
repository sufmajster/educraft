document.addEventListener('DOMContentLoaded', function () {
	const filterSelect = document.getElementById('case-study-industry-filter');
    const resultsContainer = document.getElementById('case-study-archive-results');
	if (!filterSelect || !resultsContainer || typeof educraftCaseStudyFilter === 'undefined') {
		return;
	}

	filterSelect.addEventListener('change', function () {
		resultsContainer.setAttribute('aria-busy', 'true');
        const requestBody = new URLSearchParams();
        requestBody.append('action', 'educraft_case_study_filter');
        requestBody.append('nonce', educraftCaseStudyFilter.nonce);
        requestBody.append('industry', filterSelect.value);

        fetch(educraftCaseStudyFilter.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            },
            body: requestBody.toString(),
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (json) {
                if (json && json.success && json.data && typeof json.data.html === 'string') {
                    resultsContainer.innerHTML = json.data.html;
                }
            })
            .finally(function () {
                resultsContainer.setAttribute('aria-busy', 'false');
            });
	});
});