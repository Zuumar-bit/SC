import { PIECES, INITIAL_BOARD, UNICODE_PIECES } from './pieces.js';

const boardElement = document.getElementById('chess-board');
const statusElement = document.getElementById('status');
const turnIndicator = document.getElementById('turn-indicator');
const whiteCapturedElement = document.getElementById('white-captured');
const blackCapturedElement = document.getElementById('black-captured');

let state = {
    board: JSON.parse(JSON.stringify(INITIAL_BOARD)),
    turn: PIECES.WHITE,
    selectedSquare: null,
    validMoves: [],
    captured: { w: [], b: [] },
    isGameOver: false,
    draggedPiece: null,
    dragSource: null
};

// Sound Effects
const moveSound = new Audio('data:audio/wav;base64,UklGRjIAAABXQVZFZm10IBIAAAABAAEAQB8AAEAfAAABAAgAAABmYWN0BAAAAAAAAABkYXRhAAAAAA=='); // Placeholder, we will use synth

function playMoveSound() {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    osc.type = 'sine';
    osc.frequency.setValueAtTime(800, ctx.currentTime);
    osc.frequency.exponentialRampToValueAtTime(300, ctx.currentTime + 0.1);

    gain.gain.setValueAtTime(0.3, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.1);

    osc.connect(gain);
    gain.connect(ctx.destination);

    osc.start();
    osc.stop(ctx.currentTime + 0.1);
}

function playCaptureSound() {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    osc.type = 'square';
    osc.frequency.setValueAtTime(150, ctx.currentTime);
    osc.frequency.exponentialRampToValueAtTime(100, ctx.currentTime + 0.1);

    gain.gain.setValueAtTime(0.3, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.15);

    osc.connect(gain);
    gain.connect(ctx.destination);

    osc.start();
    osc.stop(ctx.currentTime + 0.15);
}


function initGame() {
    renderBoard();
    updateStatus();
}

function renderBoard() {
    boardElement.innerHTML = '';

    state.board.forEach((row, rowIndex) => {
        row.forEach((piece, colIndex) => {
            const square = document.createElement('div');
            const isDark = (rowIndex + colIndex) % 2 === 1;
            square.className = `square ${isDark ? 'dark' : 'light'}`;
            square.dataset.row = rowIndex;
            square.dataset.col = colIndex;

            // Highlight valid moves
            const move = state.validMoves.find(m => m.row === rowIndex && m.col === colIndex);
            if (move) {
                square.classList.add('valid-move');
                if (piece) square.classList.add('capture');
            }

            if (piece) {
                const pieceSpan = document.createElement('span');
                pieceSpan.className = 'piece piece-anim';
                pieceSpan.textContent = UNICODE_PIECES[piece];
                pieceSpan.style.color = piece[0] === 'w' ? '#fff' : '#000';
                pieceSpan.draggable = true;

                // Text shadow for visibility
                if (piece[0] === 'w') {
                    pieceSpan.style.textShadow = '0 0 2px #000';
                } else {
                    pieceSpan.style.textShadow = '0 0 2px #fff';
                }

                // Drag Events
                pieceSpan.addEventListener('dragstart', (e) => handleDragStart(e, rowIndex, colIndex));
                pieceSpan.addEventListener('dragend', handleDragEnd);

                square.appendChild(pieceSpan);
            }

            // Drop Events (on square)
            square.addEventListener('dragover', handleDragOver);
            square.addEventListener('drop', (e) => handleDrop(e, rowIndex, colIndex));

            // Click Events
            square.addEventListener('click', () => handleSquareClick(rowIndex, colIndex));

            if (state.selectedSquare &&
                state.selectedSquare.row === rowIndex &&
                state.selectedSquare.col === colIndex) {
                square.classList.add('selected');
            }

            boardElement.appendChild(square);
        });
    });
}

// DRAG AND DROP HANDLERS
function handleDragStart(e, row, col) {
    if (state.isGameOver) {
        e.preventDefault();
        return;
    };

    const piece = state.board[row][col];
    if (!piece || piece[0] !== state.turn) {
        e.preventDefault();
        return;
    }

    state.draggedPiece = piece;
    state.dragSource = { row, col };

    // Select the piece as if clicked
    state.selectedSquare = { row, col };
    state.validMoves = calculateValidMoves(row, col, piece);

    // Add valid-move class to squares immediately for visual feedback
    // (Optimization: modify DOM directly to avoid full re-render flickering during drag)
    state.validMoves.forEach(m => {
        const sq = document.querySelector(`.square[data-row="${m.row}"][data-col="${m.col}"]`);
        if (sq) sq.classList.add('valid-move');
        if (state.board[m.row][m.col]) sq?.classList.add('capture');
    });

    e.dataTransfer.effectAllowed = 'move';
    setTimeout(() => {
        e.target.style.opacity = '0.5';
    }, 0);
}

function handleDragEnd(e) {
    e.target.style.opacity = '1';
    state.draggedPiece = null;
    state.dragSource = null;
    // Don't clear selection on end, let user click if they changed mind
    // But we should re-render to clean up messy DOM manipulations if any
    renderBoard();
}

function handleDragOver(e) {
    e.preventDefault();
}

function handleDrop(e, row, col) {
    e.preventDefault();
    if (!state.dragSource) return;

    const move = state.validMoves.find(m => m.row === row && m.col === col);
    if (move) {
        executeMove(state.dragSource, { row, col });
    }
}

// CLICK HANDLER
function handleSquareClick(row, col) {
    if (state.isGameOver) return;

    const piece = state.board[row][col];
    const isOwnPiece = piece && piece[0] === state.turn;

    // Select piece
    if (isOwnPiece) {
        state.selectedSquare = { row, col };
        state.validMoves = calculateValidMoves(row, col, piece);
        renderBoard(); // Re-render to show selection and moves
        return;
    }

    // Move piece if a valid move is selected
    if (state.selectedSquare) {
        const move = state.validMoves.find(m => m.row === row && m.col === col);
        if (move) {
            executeMove(state.selectedSquare, { row, col });
        } else {
            // Deselect if clicking elsewhere invalid
            state.selectedSquare = null;
            state.validMoves = [];
            renderBoard();
        }
    }
}

function calculateValidMoves(row, col, piece) {
    const moves = [];
    const color = piece[0];
    const type = piece[1];

    // Helper to check if a move is on board
    const isValidPos = (r, c) => r >= 0 && r < 8 && c >= 0 && c < 8;

    // Helper to add move if valid
    const addMove = (r, c) => {
        if (!isValidPos(r, c)) return false; // Out of bounds
        const target = state.board[r][c];
        if (target && target[0] === color) return false; // Blocked by own piece

        moves.push({ row: r, col: c });
        return !target; // Return true if valid and empty (for sliding pieces to continue)
    };

    if (type === PIECES.PAWN) {
        const direction = color === PIECES.WHITE ? -1 : 1;
        // Move forward 1
        if (isValidPos(row + direction, col) && !state.board[row + direction][col]) {
            moves.push({ row: row + direction, col: col });
            // Move forward 2 (if at start)
            if ((color === PIECES.WHITE && row === 6) || (color === PIECES.BLACK && row === 1)) {
                if (!state.board[row + (direction * 2)][col]) {
                    moves.push({ row: row + (direction * 2), col: col });
                }
            }
        }
        // Captures
        [[direction, -1], [direction, 1]].forEach(([rD, cD]) => {
            const r = row + rD;
            const c = col + cD;
            if (isValidPos(r, c)) {
                const target = state.board[r][c];
                if (target && target[0] !== color) {
                    moves.push({ row: r, col: c });
                }
            }
        });
    }

    if (type === PIECES.KNIGHT) {
        [[-2, -1], [-2, 1], [-1, -2], [-1, 2], [1, -2], [1, 2], [2, -1], [2, 1]]
            .forEach(([rD, cD]) => addMove(row + rD, col + cD));
    }

    if (type === PIECES.KING) {
        [[-1, -1], [-1, 0], [-1, 1], [0, -1], [0, 1], [1, -1], [1, 0], [1, 1]]
            .forEach(([rD, cD]) => addMove(row + rD, col + cD));
    }

    if (type === PIECES.ROOK || type === PIECES.QUEEN) {
        [[-1, 0], [1, 0], [0, -1], [0, 1]].forEach(([rD, cD]) => {
            let r = row + rD, c = col + cD;
            while (addMove(r, c)) { r += rD; c += cD; }
        });
    }

    if (type === PIECES.BISHOP || type === PIECES.QUEEN) {
        [[-1, -1], [-1, 1], [1, -1], [1, 1]].forEach(([rD, cD]) => {
            let r = row + rD, c = col + cD;
            while (addMove(r, c)) { r += rD; c += cD; }
        });
    }

    return moves;
}

function executeMove(from, to) {
    const piece = state.board[from.row][from.col];
    const target = state.board[to.row][to.col];

    // Capture logic
    if (target) {
        state.captured[state.turn === PIECES.WHITE ? 'w' : 'b'].push(target);
        updateCapturedDisplay();
        playCaptureSound();
    } else {
        playMoveSound();
    }

    // Move logic
    state.board[to.row][to.col] = piece;
    state.board[from.row][from.col] = null;

    // Pawn Promotion (Auto-Queen)
    if (piece[1] === PIECES.PAWN) {
        if ((piece[0] === PIECES.WHITE && to.row === 0) || (piece[0] === PIECES.BLACK && to.row === 7)) {
            state.board[to.row][to.col] = piece[0] + PIECES.QUEEN;
        }
    }

    // Switch turn
    state.turn = state.turn === PIECES.WHITE ? PIECES.BLACK : PIECES.WHITE;
    state.selectedSquare = null;
    state.validMoves = [];

    renderBoard();
    updateStatus();
    checkGameStatus();
}

function updateStatus() {
    const turnText = state.turn === PIECES.WHITE ? "White's Turn" : "Black's Turn";
    statusElement.textContent = turnText;
    turnIndicator.className = `turn-indicator ${state.turn === PIECES.WHITE ? 'turn-white' : 'turn-black'}`;
}

function updateCapturedDisplay() {
    whiteCapturedElement.textContent = state.captured.w.map(p => UNICODE_PIECES[p]).join(' ');
    blackCapturedElement.textContent = state.captured.b.map(p => UNICODE_PIECES[p]).join(' ');
}

function checkGameStatus() {
    // Placeholder for game over logic
}

document.getElementById('reset-btn').addEventListener('click', () => {
    state = {
        board: JSON.parse(JSON.stringify(INITIAL_BOARD)),
        turn: PIECES.WHITE,
        selectedSquare: null,
        validMoves: [],
        captured: { w: [], b: [] },
        history: [],
        isGameOver: false
    };
    initGame();
});

initGame();
