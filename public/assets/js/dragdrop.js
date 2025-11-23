/**
 * Sistema de Drag and Drop para Cards
 */

class BoardDragDrop {
  constructor() {
    this.draggedCard = null;
    this.draggedOverCard = null;
    this.sourceContainer = null;
    this.lastX = 0;
    this.lastY = 0;
    this.currentX = 0;
    this.currentY = 0;
    this.init();
  }

  init() {
    this.initializeCards();
    this.initializeContainers();
  }

  initializeCards() {
    const cards = document.querySelectorAll(".board-card");

    cards.forEach((card) => {
      // Tornar o card arrastável
      card.setAttribute("draggable", "true");

      // Eventos de drag
      card.addEventListener("dragstart", (e) => this.handleDragStart(e));
      card.addEventListener("dragend", (e) => this.handleDragEnd(e));
      card.addEventListener("dragover", (e) => this.handleDragOver(e));
      card.addEventListener("drop", (e) => this.handleDrop(e));
      card.addEventListener("dragleave", (e) => this.handleDragLeave(e));
    });
  }

  initializeContainers() {
    const containers = document.querySelectorAll(".board-cards-container");

    containers.forEach((container) => {
      container.addEventListener("dragover", (e) =>
        this.handleContainerDragOver(e)
      );
      container.addEventListener("drop", (e) => this.handleContainerDrop(e));
      container.addEventListener("dragleave", (e) =>
        this.handleContainerDragLeave(e)
      );
    });
  }

  handleDragStart(e) {
    this.draggedCard = e.target;
    this.sourceContainer = e.target.closest(".board-cards-container");

    // Capturar posição inicial
    this.lastX = e.clientX;
    this.lastY = e.clientY;
    this.currentX = e.clientX;
    this.currentY = e.clientY;

    e.target.classList.add("dragging");
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text/html", e.target.innerHTML);

    // Adicionar listener para rastrear movimento
    document.addEventListener("drag", this.trackDragMovement);
  }

  handleDragEnd(e) {
    e.target.classList.remove("dragging");
    e.target.classList.remove(
      "drag-left",
      "drag-right",
      "drag-up",
      "drag-down"
    );

    // Remover indicadores visuais
    document.querySelectorAll(".board-card").forEach((card) => {
      card.classList.remove("drag-over");
    });

    document.querySelectorAll(".board-cards-container").forEach((container) => {
      container.classList.remove("drag-over-container");
    });

    // Remover listener de movimento
    document.removeEventListener("drag", this.trackDragMovement);

    this.draggedCard = null;
    this.sourceContainer = null;
  }

  handleDragOver(e) {
    if (e.preventDefault) {
      e.preventDefault();
    }

    e.dataTransfer.dropEffect = "move";

    // Atualizar posição atual e calcular direção
    if (e.clientX !== 0 && e.clientY !== 0) {
      this.updateDragDirection(e.clientX, e.clientY);
    }

    const card = e.target.closest(".board-card");
    if (card && card !== this.draggedCard) {
      card.classList.add("drag-over");
      this.draggedOverCard = card;
    }

    return false;
  }

  handleDragLeave(e) {
    const card = e.target.closest(".board-card");
    if (card) {
      card.classList.remove("drag-over");
    }
  }

  handleDrop(e) {
    if (e.stopPropagation) {
      e.stopPropagation();
    }

    e.preventDefault();

    const targetCard = e.target.closest(".board-card");

    if (targetCard && this.draggedCard && targetCard !== this.draggedCard) {
      const container = targetCard.closest(".board-cards-container");
      const allCards = Array.from(container.querySelectorAll(".board-card"));
      const draggedIndex = allCards.indexOf(this.draggedCard);
      const targetIndex = allCards.indexOf(targetCard);

      // Inserir antes ou depois baseado na posição
      if (draggedIndex < targetIndex) {
        targetCard.parentNode.insertBefore(
          this.draggedCard,
          targetCard.nextSibling
        );
      } else {
        targetCard.parentNode.insertBefore(this.draggedCard, targetCard);
      }

      // Salvar a nova ordem
      this.saveCardOrder(container);
    }

    targetCard?.classList.remove("drag-over");

    return false;
  }

  handleContainerDragOver(e) {
    if (e.preventDefault) {
      e.preventDefault();
    }

    e.dataTransfer.dropEffect = "move";

    const container = e.target.closest(".board-cards-container");
    if (container) {
      container.classList.add("drag-over-container");
    }

    return false;
  }

  handleContainerDragLeave(e) {
    const container = e.target.closest(".board-cards-container");
    const relatedTarget = e.relatedTarget;

    // Só remove se realmente saiu do container
    if (container && !container.contains(relatedTarget)) {
      container.classList.remove("drag-over-container");
    }
  }

  handleContainerDrop(e) {
    if (e.stopPropagation) {
      e.stopPropagation();
    }

    e.preventDefault();

    const container = e.target.closest(".board-cards-container");

    if (container && this.draggedCard) {
      // Verificar se não está dropando em um card
      const targetCard = e.target.closest(".board-card");

      if (!targetCard) {
        // Adicionar ao final do container
        container.appendChild(this.draggedCard);

        // Salvar a nova ordem e turma
        this.saveCardOrder(container);
      }
    }

    container?.classList.remove("drag-over-container");

    return false;
  }

  saveCardOrder(container) {
    const turmaId = container.dataset.listId;
    const cards = Array.from(container.querySelectorAll(".board-card"));

    const cardData = cards.map((card, index) => ({
      id: card.dataset.cardId,
      turma_id: turmaId,
      ordem: index + 1,
    }));

    // Enviar para o backend
    this.updateCardsOnServer(cardData);
  }

  async updateCardsOnServer(cardData) {
    try {
      const response = await fetch("/cards/reorder", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({cards: cardData}),
      });

      if (!response.ok) {
        console.error("Erro ao atualizar ordem dos cards");
      }
    } catch (error) {
      console.error("Erro ao salvar ordem dos cards:", error);
    }
  }

  // Rastrear movimento do drag
  trackDragMovement = (e) => {
    if (e.clientX !== 0 && e.clientY !== 0) {
      this.updateDragDirection(e.clientX, e.clientY);
    }
  };

  // Atualizar direção baseado no movimento
  updateDragDirection(x, y) {
    if (!this.draggedCard) return;

    const deltaX = x - this.currentX;
    const deltaY = y - this.currentY;

    // Atualizar posição
    this.currentX = x;
    this.currentY = y;

    // Remover todas as classes de direção
    this.draggedCard.classList.remove(
      "drag-left",
      "drag-right",
      "drag-up",
      "drag-down"
    );

    // Determinar direção dominante
    if (Math.abs(deltaX) > Math.abs(deltaY)) {
      // Movimento horizontal é dominante
      if (deltaX > 0) {
        this.draggedCard.classList.add("drag-right");
      } else if (deltaX < 0) {
        this.draggedCard.classList.add("drag-left");
      }
    } else {
      // Movimento vertical é dominante
      if (deltaY > 0) {
        this.draggedCard.classList.add("drag-down");
      } else if (deltaY < 0) {
        this.draggedCard.classList.add("drag-up");
      }
    }
  }

  // Método para reinicializar após adicionar novos cards dinamicamente
  refresh() {
    this.init();
  }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener("DOMContentLoaded", () => {
  window.boardDragDrop = new BoardDragDrop();
});

// Exportar para uso global
window.BoardDragDrop = BoardDragDrop;
