<?php

return [

    'accepted' => ':attributeは承認する必要があります。',
    'accepted_if' => ':otherが:valueのとき、:attributeは承認する必要があります。',
    'active_url' => ':attributeは有効なURLでなければなりません。',
    'after' => ':attributeは:date以降の日付でなければなりません。',
    'after_or_equal' => ':attributeは:date以降または同じ日付でなければなりません。',
    'alpha' => ':attributeには文字のみを含めることができます。',
    'alpha_dash' => ':attributeには文字、数字、ダッシュ、アンダースコアのみを含めることができます。',
    'alpha_num' => ':attributeには文字と数字のみを含めることができます。',
    'any_of' => ':attributeが無効です。',
    'array' => ':attributeは配列でなければなりません。',
    'ascii' => ':attributeには1バイトの英数字と記号のみを含めることができます。',
    'before' => ':attributeは:date以前の日付でなければなりません。',
    'before_or_equal' => ':attributeは:date以前または同じ日付でなければなりません。',
    'between' => [
        'array' => ':attributeの要素数は:minから:maxの間でなければなりません。',
        'file' => ':attributeのサイズは:min〜:maxキロバイトでなければなりません。',
        'numeric' => ':attributeは:min〜:maxの間でなければなりません。',
        'string' => ':attributeは:min〜:max文字でなければなりません。',
    ],
    'boolean' => ':attributeはtrueかfalseでなければなりません。',
    'can' => ':attributeには許可されていない値が含まれています。',
    'confirmed' => ':attributeの確認が一致しません。',
    'contains' => ':attributeに必要な値が含まれていません。',
    'current_password' => '現在のパスワードが正しくありません。',
    'date' => ':attributeは有効な日付でなければなりません。',
    'date_equals' => ':attributeは:dateと同じ日付でなければなりません。',
    'date_format' => ':attributeの形式は:formatと一致しなければなりません。',
    'decimal' => ':attributeは:decimal桁の小数でなければなりません。',
    'declined' => ':attributeは拒否する必要があります。',
    'declined_if' => ':otherが:valueのとき、:attributeは拒否する必要があります。',
    'different' => ':attributeと:otherは異なっていなければなりません。',
    'digits' => ':attributeは:digits桁でなければなりません。',
    'digits_between' => ':attributeは:min〜:max桁でなければなりません。',
    'dimensions' => ':attributeの画像サイズが無効です。',
    'distinct' => ':attributeに重複した値があります。',
    'doesnt_end_with' => ':attributeは次のいずれかで終わってはいけません: :values。',
    'doesnt_start_with' => ':attributeは次のいずれかで始まってはいけません: :values。',
    'email' => ':attributeは有効なメールアドレスでなければなりません。',
    'ends_with' => ':attributeは次のいずれかで終わらなければなりません: :values。',
    'enum' => '選択された:attributeは無効です。',
    'exists' => '選択された:attributeは無効です。',
    'extensions' => ':attributeは次の拡張子のいずれかでなければなりません: :values。',
    'file' => ':attributeはファイルでなければなりません。',
    'filled' => ':attributeには値が必要です。',
    'gt' => [
        'array' => ':attributeは:value個以上の要素を含める必要があります。',
        'file' => ':attributeは:valueキロバイトより大きくなければなりません。',
        'numeric' => ':attributeは:valueより大きくなければなりません。',
        'string' => ':attributeは:value文字より多くなければなりません。',
    ],
    'gte' => [
        'array' => ':attributeは:value個以上の要素が必要です。',
        'file' => ':attributeは:valueキロバイト以上でなければなりません。',
        'numeric' => ':attributeは:value以上でなければなりません。',
        'string' => ':attributeは:value文字以上でなければなりません。',
    ],
    'hex_color' => ':attributeは有効な16進数の色でなければなりません。',
    'image' => ':attributeは画像でなければなりません。',
    'in' => '選択された:attributeは無効です。',
    'in_array' => ':attributeは:otherに存在する必要があります。',
    'integer' => ':attributeは整数でなければなりません。',
    'ip' => ':attributeは有効なIPアドレスでなければなりません。',
    'ipv4' => ':attributeは有効なIPv4アドレスでなければなりません。',
    'ipv6' => ':attributeは有効なIPv6アドレスでなければなりません。',
    'json' => ':attributeは有効なJSON文字列でなければなりません。',
    'list' => ':attributeはリストでなければなりません。',
    'lowercase' => ':attributeは小文字でなければなりません。',
    'lt' => [
        'array' => ':attributeは:value個未満の要素でなければなりません。',
        'file' => ':attributeは:valueキロバイト未満でなければなりません。',
        'numeric' => ':attributeは:value未満でなければなりません。',
        'string' => ':attributeは:value文字未満でなければなりません。',
    ],
    'lte' => [
        'array' => ':attributeは:value個以下の要素でなければなりません。',
        'file' => ':attributeは:valueキロバイト以下でなければなりません。',
        'numeric' => ':attributeは:value以下でなければなりません。',
        'string' => ':attributeは:value文字以下でなければなりません。',
    ],
    'mac_address' => ':attributeは有効なMACアドレスでなければなりません。',
    'max' => [
        'array' => ':attributeは:max個以上含めることはできません。',
        'file' => ':attributeは:maxキロバイトを超えてはなりません。',
        'numeric' => ':attributeは:maxを超えてはなりません。',
        'string' => ':attributeは:max文字を超えてはなりません。',
    ],
    'min' => [
        'array' => ':attributeは少なくとも:min個の要素が必要です。',
        'file' => ':attributeは少なくとも:minキロバイト必要です。',
        'numeric' => ':attributeは少なくとも:minでなければなりません。',
        'string' => ':attributeは少なくとも:min文字必要です。',
    ],
    'numeric' => ':attributeは数値でなければなりません。',
    'regex' => ':attributeの形式が無効です。',
    'required' => ':attributeは必須です。',
    'same' => ':attributeと:otherは一致しなければなりません。',
    'size' => [
        'array' => ':attributeは:size個の要素を含む必要があります。',
        'file' => ':attributeは:sizeキロバイトでなければなりません。',
        'numeric' => ':attributeは:sizeでなければなりません。',
        'string' => ':attributeは:size文字でなければなりません。',
    ],
    'string' => ':attributeは文字列でなければなりません。',
    'timezone' => ':attributeは有効なタイムゾーンでなければなりません。',
    'unique' => ':attributeはすでに使用されています。',
    'url' => ':attributeは有効なURLでなければなりません。',
    'uuid' => ':attributeは有効なUUIDでなければなりません。',

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'カスタムメッセージ',
        ],
    ],

    'attributes' => [],

];
