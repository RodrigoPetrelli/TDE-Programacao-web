document.addEventListener('DOMContentLoaded', () => {
    // Check if user is logged in
    if (!sessionStorage.getItem('user_id')) {
        window.location.href = 'index.html';
        return;
    }

    // Display user name
    const userName = sessionStorage.getItem('user_name');
    document.getElementById('userName').textContent = userName || 'Usuário';

    // Fetch account balance
    fetchBalance();
    
    // Fetch recent transactions
    fetchTransactions();
});

function fetchBalance() {
    fetch('api/balance.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('balance').textContent = 
                new Intl.NumberFormat('pt-BR', { 
                    style: 'currency', 
                    currency: 'BRL' 
                }).format(data.balance);
        })
        .catch(error => console.error('Error:', error));
}

function fetchTransactions() {
    fetch('api/transactions.php')
        .then(response => response.json())
        .then(data => {
            const list = document.getElementById('transactionsList');
            list.innerHTML = data.transactions.map(transaction => `
                <div class="transaction-item">
                    <div>
                        <strong>${transaction.description}</strong>
                        <div>${new Date(transaction.date).toLocaleDateString('pt-BR')}</div>
                    </div>
                    <div class="${transaction.type === 'credit' ? 'credit' : 'debit'}">
                        ${transaction.type === 'credit' ? '+' : '-'} 
                        ${new Intl.NumberFormat('pt-BR', { 
                            style: 'currency', 
                            currency: 'BRL' 
                        }).format(transaction.amount)}
                    </div>
                </div>
            `).join('');
        })
        .catch(error => console.error('Error:', error));
}

function logout() {
    sessionStorage.clear();
    window.location.href = 'index.html';
}