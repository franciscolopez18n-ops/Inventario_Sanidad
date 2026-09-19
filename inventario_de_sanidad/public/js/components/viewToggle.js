export class ViewToggle {
    #views;

    constructor(views) {
        this.#views = views;
    }

    init(defaultIndex = 0) {
        this.#views.forEach((view, index) => {
            view.button.addEventListener("click", (e) => {
                e.preventDefault();
                this.#activate(index);
            });
        });

        this.#activate(defaultIndex);
    }

    #activate(activeIndex) {
        this.#views.forEach((view, index) => {
            let isActive = index === activeIndex;
            view.container.classList.toggle("hidden", !isActive);
            view.button.classList.toggle("active", isActive);
        });
    }
}