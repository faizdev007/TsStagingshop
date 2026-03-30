<style>
.filter-grid {
    background: #fff;
    padding: 15px 0;
    border-bottom: 1px solid #e6e6e6;
    margin-bottom: 20px;
}

.filter-card {
    background: #fff;
    border: 1px solid #e6e6e6;
    border-radius: 4px;
    padding: 12px 15px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-card:hover {
    border-color: #1da1f2;
    box-shadow: 0 2px 4px rgba(29, 161, 242, 0.1);
}

.filter-title {
    font-size: 14px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

.selected-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.selected-text {
    font-size: 13px;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 80%;
}

.total-count {
    font-size: 12px;
    color: #1da1f2;
    background: rgba(29, 161, 242, 0.1);
    padding: 2px 6px;
    border-radius: 12px;
}

/* Modal Styles */
.filter-modal .modal-dialog {
    max-width: 400px;
}

.filter-modal .modal-content {
    border-radius: 8px;
}

.filter-modal .modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid #e6e6e6;
}

.filter-modal .modal-title {
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
}

.filter-modal .modal-body {
    padding: 20px;
    max-height: 400px;
    overflow-y: auto;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.filter-option {
    display: flex;
    align-items: center;
    padding: 8px 0;
}

.filter-option input[type="checkbox"],
.filter-option input[type="radio"] {
    margin-right: 10px;
}

.filter-option label {
    font-size: 14px;
    color: #2c3e50;
    display: flex;
    justify-content: space-between;
    width: 100%;
    cursor: pointer;
}

.filter-option .count {
    color: #666;
    font-size: 13px;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .filter-card {
        margin-bottom: 10px;
    }
    
    .filter-modal .modal-dialog {
        margin: 10px;
    }
}
</style>
