lightbox.option({
	'resizeDuration': 10,
	'wrapAround': true,
	'albumLabel': "%1/%2",
});

const showTab = (elTabBtn) => {
	const elTab = elTabBtn.closest('.tab');
	if (elTabBtn.classList.contains('tab-btn-active')) {
		return;
	}
	const targetId = elTabBtn.dataset.targetId;
	const elTabPane = elTab.querySelector(`.tab-pane[data-id="${targetId}"]`);
	if (elTabPane) {
		const elTabBtnActive = elTab.querySelector('.tab-btn-active');
		elTabBtnActive.classList.remove('tab-btn-active');
		const elTabPaneShow = elTab.querySelector('.tab-pane-show');
		elTabPaneShow.classList.remove('tab-pane-show');
		elTabBtn.classList.add('tab-btn-active');
		elTabPane.classList.add('tab-pane-show');
	}
}

document.addEventListener('click', (e) => {
	if (e.target && !e.target.closest('.tab-btn')) {
		return;
	}
	const elTabBtn = e.target.closest('.tab-btn');
	showTab(elTabBtn);
});