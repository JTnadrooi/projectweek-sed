class account {
    constructor() {
        this.username = '';
        this.password = '';
        this.email = '';
    }

    setUsername(username) {
        this.username = username;
    }

    setPassword(password) {
        this.password = password;
    }

    setEmail(email) {
        this.email = email;
    }

    getUsername() {
        return this.username;
    }

    getPassword() {
        return this.password;
    }

    getEmail() {
        return this.email;
    }
}

class accountManager {
    constructor() {
        this.accounts = [];
    }

    addAccount(account) {
        this.accounts.push(account);
    }

    getAccount(username) {
        return this.accounts.find(acc => acc.getUsername() === username);
    }

    removeAccount(username) {
        this.accounts = this.accounts.filter(acc => acc.getUsername() !== username);
    }

    listAccounts() {
        return this.accounts.map(acc => acc.getUsername());
    }
}

let button = document.getElementById('addAccountButton');
button.addEventListener('click', () => {
    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;
    let email = document.getElementById('email').value;

    if (username && password && email) {
        let newAccount = new account();
        newAccount.setUsername(username);
        newAccount.setPassword(password);
        newAccount.setEmail(email);

        accountManagerInstance.addAccount(newAccount);
        alert('account gemaakt!');
    } else {
        alert('shijf alles nog');
    }
});