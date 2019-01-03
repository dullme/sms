export default {
    select: function (
        data,
        tag,
        url,
        key,
        value,
        allowClear = false,
        placeholder = '',
        minimumInputLength = 1,
        language = "zh-CN",
        formatRepo = null,
        formatRepoSelection = null
    ) {
        $(tag).select2({
            data: data,
            placeholder: placeholder,
            allowClear: allowClear,
            minimumInputLength: minimumInputLength,
            language: language,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        key: params.term,
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            templateResult: function (repo) {
                return formatRepo == null ? defaultFormatRepo(repo, key, value) : formatRepo(repo)
            },
            templateSelection: function (repo) {
                return formatRepoSelection == null ? defaultFormatRepoSelection(repo, key) : formatRepoSelection(repo)
            },
            escapeMarkup: function (markup) { return markup; }
        });
    },

    /**
     * 格式化数字自动补零
     * @param num 数字
     * @param n 位数
     * @returns {string}
     */
    prefixInteger(num, n) {
        return (Array(n).join(0) + num).slice(-n);
    },
}

function defaultFormatRepo (repo, key, value) {
    if (repo.loading) return repo.text;
    return repo[key]+'：'+repo[value];
}

function defaultFormatRepoSelection (repo, key) {
    return repo[key] || repo.text;
}
