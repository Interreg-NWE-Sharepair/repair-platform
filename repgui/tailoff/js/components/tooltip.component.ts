export class TooltipComponent {

  constructor() {
    const triggers = document.querySelectorAll('[data-s-tooltip]');
    Array.from(triggers).forEach((t: HTMLElement) => {
      this.initTooltip(t);
    });
  }


  private initTooltip(element: HTMLElement) {
    element.setAttribute('aria-expanded', 'false');

    element.addEventListener('click', (e) => {
      e.preventDefault();
      this.toggleTooltip(element);
    });
  }

  private toggleTooltip(element: HTMLElement) {
    
    const tooltipElement = element.nextElementSibling as HTMLDivElement;

    if (tooltipElement.classList.contains('hidden')) {
      tooltipElement.classList.remove('hidden');
      element.setAttribute('aria-expanded', 'true');
      element.classList.add('open');
    } else {
      tooltipElement.classList.add('hidden');
      element.setAttribute('aria-expanded', 'false');
      element.classList.remove('open');
    }
  }
}