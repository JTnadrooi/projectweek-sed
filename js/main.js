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