let boardState = {
  isEditing: false,
  currentCard: null,
};

function openCardModal(cardElement = null) {
  const cardId = cardElement ? cardElement.getAttribute("data-card-id") : null;
  const isCreating = !cardId;
  const url = isCreating ? '/cards/create' : `/cards/details/${cardId}`;
  console.log(isCreating)

  fetch(url)
    .then(function (response) {
      if (!response.ok) {
        throw new Error("Erro na requisição: " + response.status);
      }
      return response.text();
    })
    .then(function (html) {
      document.getElementById("card-modal").innerHTML = html;
      document.getElementById("card-modal").style.display = "flex";

      if (isCreating) {
        toggleCardEditing();
      }

      if (!isCreating) {
        carregarDropdownModal(cardId);
      }

      $(".ui.dropdown").dropdown();

      validateModal();


    })
    .catch(function (error) {
      console.error('Erro ao carregar detalhes do card:', error);
    });
}

function validateModal() {
  $('#form').form({
    inline: false,

    fields: {
      'card-edit-title': {
        rules: [
          {
            type: 'empty',
            prompt: 'O título não pode ficar vazio.'
          }
        ]
      },
      'card-field-turma': {
        rules: [
          {
            type: 'empty',
            prompt: 'Selecione uma turma.'
          }
        ]
      },
      'card-field-curso': {
        rules: [
          {
            type: 'empty',
            prompt: 'Selecione um curso.'
          }
        ]
      },
      'card-field-uc': {
        rules: [
          {
            type: 'empty',
            prompt: 'Selecione uma UC.'
          }
        ]
      }
    }
  });

}

function carregarDropdownModal(id_valor) {
  fetch(`/cards/show/${id_valor}`)
    .then(function (response) {
      if (!response.ok) {
        throw new Error("Erro na requisição: " + response.status);
      }
      return response.json();
    })
    .then(function (data) {
      boardState.currentCard = data.card || null;
      Object.keys(data.drop).forEach(function (key) {
        const values = data.drop[key].map(function (item) {
          const isSelected = data.card ? (key === 'drop-ucs' && data.card.uc_id === item.id) || (key === 'drop-cursos' && data.card.curso_id === item.id) || (key === 'drop-turmas' && data.card.turma_id === item.id) : false;
          return {
            name: item.nome_curso || item.nome || (item.sigla + ' - ' + item.nome_completo),
            value: item.id,
            selected: isSelected,
          };
        });
        $(".ui.dropdown." + key).dropdown({values: values});
      });

      if (data.card && data.card.uc_id) {
        handleUcChange(data.card.uc_id, data.card);
      }
    })
    .catch(function (error) {
      console.error("Ocorreu um erro:", error);
    });
}

function closeCardModal() {
  const modal = document.getElementById("card-modal");
  if (modal) {
    modal.style.display = "none";
    $('#card-modal').off('change', '#card-field-uc');
  }
  boardState.isEditing = false;
  boardState.currentCard = null;
}

function populateMultiSelect(items, cardData) {
  const relatedKeys = ['indicadores', 'conhecimentos', 'habilidades', 'atitudes'];

  relatedKeys.forEach(function (key) {
    const dropdown = $(".ui.dropdown." + key);
    if (dropdown.length === 0) return;

    const currentItems = (items && items[key]) ? items[key] : [];
    const values = currentItems.map(function (item) {
      return {name: item.name, value: item.value};
    });

    dropdown.dropdown({values: values});
    dropdown.dropdown('clear');

    if (cardData && cardData[key] && Array.isArray(cardData[key])) {
      const selectedIds = cardData[key].map(function (id) {
        return String(id);
      });
      if (selectedIds.length > 0) {
        dropdown.dropdown('set selected', selectedIds);
      }
    }
  });
}

function handleUcChange(ucIdOrEvent, cardData) {
  let ucId;
  let isChangeEvent = typeof ucIdOrEvent === 'object' && ucIdOrEvent !== null && ucIdOrEvent.target;

  if (isChangeEvent) {
    ucId = ucIdOrEvent.target.value;
    cardData = null;
  } else {
    ucId = ucIdOrEvent;
  }

  if (!ucId) {
    populateMultiSelect({}, null);
    return;
  }

  fetch('/uc/related/' + encodeURIComponent(ucId), {
    headers: {"Accept": "application/json"}
  })
    .then(function (resp) {
      if (resp.status === 404) {
        return {};
      }
      if (!resp.ok) {
        throw new Error('Erro na requisição: ' + resp.status);
      }
      return resp.json();
    })
    .then(function (relatedData) {
      populateMultiSelect(relatedData, cardData);
    })
    .catch(function (err) {
      console.error('Falha ao buscar ou processar dados da UC:', err);
      populateMultiSelect({}, null);
    });
}

function toggleCardEditing(forceEdit = null) {
  boardState.isEditing = forceEdit !== null ? forceEdit : !boardState.isEditing;

  const viewMode = document.getElementById('card-view-mode');
  const editMode = document.getElementById('card-edit-mode');
  const toggleBtn = document.getElementById('card-edit-toggle-btn');
  const fieldSelects = document.querySelectorAll('.card-field-select, .card-field-input');
  const dropdowns = $('#card-modal .ui.dropdown');

  if (boardState.isEditing) {
    if (viewMode) viewMode.style.display = 'none';
    if (editMode) editMode.style.display = 'block';
    if (toggleBtn) toggleBtn.textContent = 'Cancelar';

    fieldSelects.forEach(field => field.disabled = false);
    dropdowns.removeClass('disabled');
  } else {
    if (viewMode) viewMode.style.display = 'block';
    if (editMode) editMode.style.display = 'none';
    if (toggleBtn) toggleBtn.textContent = 'Editar';

    fieldSelects.forEach(field => field.disabled = true);
    dropdowns.addClass('disabled');

    if (!boardState.currentCard?.id) {
      closeCardModal();
    }
  }
};

function handleCardUpdate() {
  const cardId = boardState.currentCard.id;
  if (!cardId) {
    console.error("ID do card não encontrado.");
    return;
  }

  const updatedData = {
    titulo: document.getElementById('card-edit-title').value,
    descricao: document.getElementById('card-edit-description').value,
    turma_id: document.getElementById('card-field-turma').value,
    curso_id: document.getElementById('card-field-curso').value,
    uc_id: document.getElementById('card-field-uc').value,
    aula_inicial: document.getElementById('card-field-aula-inicial').value,
    aula_final: document.getElementById('card-field-aula-final').value,
    indicadores: $('#card-field-indicadores-container').val(),
    conhecimentos: $('#card-field-conhecimentos-container').val(),
    habilidades: $('#card-field-habilidades-container').val(),
    atitudes: $('#card-field-atitudes-container').val(),
  };

  fetch(`/cards/update/${cardId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(updatedData),
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Erro ao atualizar o card');
      }
      return response.json();
    })
    .then(data => {
      console.log('Card atualizado com sucesso:', data);
      toggleCardEditing(false)
    })
    .catch(error => {
      console.error('Erro na requisição:', error);
    });
}

function handleCardCreate() {
  if (!$('#form').form('is valid')) {
    return false;
  }

  const cardData = {
    titulo: document.getElementById('card-edit-title').value,
    descricao: document.getElementById('card-edit-description').value,
    turma_id: document.getElementById('card-field-turma').value,
    curso_id: document.getElementById('card-field-curso').value,
    uc_id: document.getElementById('card-field-uc').value,
    aula_inicial: document.getElementById('card-field-aula-inicial').value,
    aula_final: document.getElementById('card-field-aula-final').value,
    indicadores: $('#card-field-indicadores-container').val(),
    conhecimentos: $('#card-field-conhecimentos-container').val(),
    habilidades: $('#card-field-habilidades-container').val(),
    atitudes: $('#card-field-atitudes-container').val(),
  };

  fetch('/cards/store', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(cardData),
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Erro ao criar o card');
      }
      return response.json();
    })
    .then(data => {
      console.log('Card criado com sucesso:', data);
      closeCardModal();
    })
    .catch(error => {
      console.error('Erro na requisição:', error);
    });
}