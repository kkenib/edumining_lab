function KeywordComponent() {
    this.attribute = {
        index: 0,
        word: '',
        frequency: 0,
        rate: 0.0,
        cumulative_rate: 0.0,
        anal_value_name: "index",
        selected: false
    }
    this.setAttribute = (o) => {
        for (const key in o) {
            this.attribute[key] = o[key]
        }
    }
}
function BasicKeywordComponent() {
    KeywordComponent.call(this)
    this.template = () => {
        const attr = this.attribute
        const analValueName = attr.anal_value_name
        return `
            <tr>
                <td class="w20p">${attr.word}</td>
                <td class="w20p">${attr.frequency}</td>
                <td class="w20p">${attr.rate}</td>
                <td class="w20p">${attr.cumulative_rate}</td>
                <td class="w20p">
                    <span>
                        <input type="checkbox" name="chk" id="${attr.index}" value="${attr[analValueName]}" ${attr.index < 10 ? "checked": ""}>
                        <label for="${attr.index}"></label>
                    </span>
                </td>
            </tr>`
    }
}
function MatrixKeywordComponent() {
    KeywordComponent.call(this)
    this.template = () => {
        const attr = this.attribute
        return `
            <tr>
                <td class="w20p">${attr.row_word}</td>
                <td class="w20p">${attr.column_word}</td>
                <td class="w20p">${attr.frequency}</td>
                <td class="w20p">${attr.rate}</td>
                <td class="w20p">
                    <span>
                        <input type="checkbox" name="chk" id="${attr.index}" value="${attr.index}" ${attr.index < 10 ? "checked": ""}>
                        <label for="${attr.index}"></label>
                    </span>
                </td>
            </tr>`
    }
}
function SimpleKeywordComponent() {
    KeywordComponent.call(this)
    this.template = () => {
        const attr = this.attribute
        const wordAttrName = attr.word_attr_name
        const chkName  = attr.chk_name
        const chkIndex = attr.chk_index
        const word = attr[wordAttrName]
        const freq = attr.frequency
        const index = attr.index
        return `
            <tr>
                <td class="w20p">${word}</td>
                <td class="w20p">${freq}</td>
                <td class="w20p">
                    <span>
                        <input type="checkbox" name="${chkName}" id="${chkName}${chkIndex}" value="${chkIndex}" ${index < 10 ? "checked": ""}>
                        <label for="${chkName}${chkIndex}"></label>
                    </span>
                </td>
            </tr>`
    }
}