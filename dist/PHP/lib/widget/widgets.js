class Widgets {
    contener = 'widgets-rang';
    api = '';
    host = '';
    data;
    platform = [];
    filial = [];
    header = true;

    constructor() {}

    startKeys() {
        let filial = '?filial=';
        this.filial.forEach((fil, i) => {
            fil = fil.replace('+', '%2B');
            filial += this.filial.length - 1 == i ? fil : fil + ';';
        });

        this.get(this.host + '/widget/' + this.api + filial).onload = request => {
            const response = request.target.responseText;
            let data = JSON.parse(response);
            this.build(data);
        };
    }

    // строим
    build(data) {
        const widgetsElement = document.querySelector(this.contener);
        //  задаем блок
        let block = this.createEl('div', 'blok-keys-widgets');

        widgetsElement.append(block);

        Object.keys(data).forEach(filial => {
            let blokcolumn = this.createEl('div', 'block-colum');

            if (this.header) {
                let h = this.createEl('h2');
                h.textContent = filial.toLocaleUpperCase();
                blokcolumn.append(h);
            }

            let blockrow = this.createEl('div', 'block-row');

            Object.keys(data[filial]).forEach(platform => {
                if (!this.excludePlatform(platform)) return;

                const part = this.createEl('div', 'part');

                let star = this.createEl('div', 'star');
                star.innerHTML = `<p class='name'>${data[filial][platform]['origin']}</p><div>
                <img class="star-img" src="${this.host + '/PHP/lib/widget/images-widgets/star.png'}"></img>
                <b>${data[filial][platform]['star']}</b>
                <p>/5</p></div>`;
                //    star.textContent = data[filial][plaform]['star']

                const img = this.createEl('img');
                img.src = data[filial][platform]['img'];

                part.append(img, star);
                blockrow.append(part);
            });
            blokcolumn.append(blockrow);
            block.append(blokcolumn);
        });
    }

    // запросы
    get(url) {
        const request = new XMLHttpRequest();
        request.open('GET', url, true);
        request.setRequestHeader('Content-type', 'application/json');
        request.send();
        return request;
    }

    createEl(tag, className = '') {
        const el = document.createElement(tag);
        el.className = className;
        return el;
    }

    excludePlatform(platform) {
        if (this.platform.length != 0) {
            return this.platform.includes(platform);
        }

        return true;
    }
}

//  let w = new Widgets();
//     w.api = '999-999-999'
//     w.filial = ['Альтамед+ на союзной']
//     w.plaform = [  ]
//     w.header = true
//     w.startKeys()
