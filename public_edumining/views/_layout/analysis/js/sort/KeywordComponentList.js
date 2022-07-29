function KeywordComponentList (componentDI) {
    let originList = []
    let sortedList = []
    const sortedState = {} // 0: 단어 정렬 미적용, 1: 단어 내림차순 정렬, 2: 단어 올림차순 정렬

    let checkAllState = false
    this.checkAll = () => {
        checkAllState = !checkAllState
        return checkAllState
    }

    this.createComponent = componentDI
    this.setSortAttribute = (attrNames) => {
        attrNames.forEach(name => sortedState[name] = 0)
    }
    let listViewId = 'data_tbody'
    this.setListViewId = (viewId) => {
        listViewId = viewId
    }
    this.push = (m) => { originList.push(m) }
    this.clear = () => {
        originList = []
        sortedList = []
        for (const key in sortedState) {
            sortedState[key] = 0
        }
        this.changeSortButton()
    }
    this.iterate = (action, component) => {
        for (const component of originList) {
            action(component)
        }
    }
    this.getCurrentList = () => {
        let sorted = false
        for (const key in sortedState) {
            if (sortedState[key] > 0) {
                sorted = true
                break
            }
        }
        return sorted ? sortedList : originList
    }
    this.sort = (targetDataType) => {
        for (const key in sortedState) {
            if (key === targetDataType) {
                sortedState[key] = sortedState[key] === 2 ? 0 : (sortedState[key] + 1)
            } else {
                sortedState[key] = 0
            }
        }

        if (sortedState[targetDataType] === 0)
            return originList

        const objectList = []
        for (const component of originList)
            objectList.push(component.attribute)

        objectList.sort((keyword1, keyword2) => {
            // console.log(keyword1)
            const word1 = keyword1[targetDataType].toLowerCase()
            const word2 = keyword2[targetDataType].toLowerCase()
            if(sortedState[targetDataType] === 1)
                return word1 < word2 ? 1 : (word1 > word2 ? -1 : 0)
            else
                return word1 < word2 ? -1 : (word1 > word2 ? 1 : 0)
        })
        sortedList = []
        for (const o of objectList) {
            const keyword = this.createComponent()
            keyword.setAttribute(o)
            sortedList.push(keyword)
        }
        // console.log("Length: " + originList.length + ' / ' + sortedList.length)
        return sortedList
    }
    this.changeSortButton = () => {
        for (const key in sortedState) {
            const sortButton = document.getElementById(`sort_by_${key}`)
            sortButton.innerHTML = sortedState[key] === 0 ? '▼▲' : (sortedState[key] === 1 ? '▼' : '▲')
        }
    }
    this.update = () => {
        let html = '';
        const list = this.getCurrentList()
        if (list.length === 0) {
            html = this.noData()
        } else {
            for (let index = 0; index < list.length; index++) {
                const component = list[index]
                html += component.template()
            }
        }
        document.getElementById(listViewId).innerHTML = html
        this.changeSortButton()

        this.setCheckedKeywordMonitor()
    }
    this.setCheckedKeywordMonitor = () => {
        let html = '';
        const list = this.getCurrentList()
        if (list.length === 0) {
            html = this.noData()
        } else {
            for (let index = 0; index < list.length; index++) {
                const component = list[index]
                const checkViewId = component.attribute.check_view_id
                const checkDom = document.getElementById(checkViewId)
                checkDom.addEventListener("change", this.updateCheckedCount)
            }
            this.updateCheckedCount()
        }
    }

    this.checkBoxName = "chk"
    this.checkedCountViewId = "checked_keyword_count"
    this.updateCheckedCount = () => {
        const totalCheckDomCount = document.getElementsByName(this.checkBoxName).length
        const checkedCount = document.querySelectorAll(`input[name="${this.checkBoxName}"]:checked`).length
        const checkKeywordCountDom = document.getElementById(this.checkedCountViewId)
        checkKeywordCountDom.innerHTML = `${checkedCount}/${totalCheckDomCount}`
    }
    this.noData = () => {
        return `<tr><td class="list_none" colspan="5">데이터가 없습니다.</td></tr>`
    }
    this.attachEvent = (eventObject) => {
        for (const key in eventObject) {
            let bindAction = undefined
            const eventType = eventObject[key].event_type
            if (eventType === "click") {
                const dataName = eventObject[key].sort_data
                bindAction = this.sortUpdate.bind(this, dataName)
            } else {
                const chkName = eventObject[key].chk_name
                bindAction = this.checkAll.bind(this, chkName)
            }
            const targetDom = document.getElementById(key)
            try {
                // console.log(targetDom)
                targetDom.removeEventListener(eventType, bindAction)
                targetDom.addEventListener(eventType, bindAction)
            }catch (error) {
                console.error(error)
            }
        }
    }
    this.sortUpdate = (dataType) => {
        this.sort(dataType)
        this.update()
    }
    this.checkAll = (chkBoxName) => {
        checkAllState = !checkAllState
        const chkBox = document.getElementsByName(chkBoxName)
        chkBox.forEach((checkbox) => {
            checkbox.checked = checkAllState
        })
        this.updateCheckedCount()
    }
}
function BasicKeywordComponentList() {
    KeywordComponentList.call(this, () => { return new BasicKeywordComponent() })
    this.setSortAttribute(["word"])
}
function MatrixKeywordComponentList() {
    KeywordComponentList.call(this, () => { return new MatrixKeywordComponent() })
    this.setSortAttribute(["row_word", "column_word"])
}
function SimpleKeywordComponentList() {
    KeywordComponentList.call(this, () => { return new SimpleKeywordComponent() })
    this.noData = () => {
        return `<tr><td colspan="3" class="list_none">데이터가 없습니다.</td></tr>`
    }
}
const basicKeywordList = (function () {
    let instance = undefined;
    return {
        getInstance: function () {
            if (instance === undefined) {
                instance = new BasicKeywordComponentList()
            }
            return instance
        }
    };
})();
const matrixKeywordList = (function () {
    let instance = undefined;
    return {
        getInstance: function () {
            if (instance === undefined) {
                instance = new MatrixKeywordComponentList()
            }
            return instance
        }
    };
})();
const positiveSimpleKeywordList = (function () {
    let instance = undefined;
    return {
        getInstance: function () {
            if (instance === undefined) {
                instance = new SimpleKeywordComponentList()
                instance.setSortAttribute(["positive_word"])

                instance.checkBoxName = "chk_positive"
                instance.checkedCountViewId = "checked_positive_keyword_count"

            }
            return instance
        }
    };
})();
const negativeSimpleKeywordList = (function () {
    let instance = undefined;
    return {
        getInstance: function () {
            if (instance === undefined) {
                instance = new SimpleKeywordComponentList()
                instance.setSortAttribute(["negative_word"])

                instance.checkBoxName = "chk_negative"
                instance.checkedCountViewId = "checked_negative_keyword_count"
            }
            return instance
        }
    };
})();