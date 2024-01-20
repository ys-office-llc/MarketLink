class Object

  def to_bool

    case self
    when 'true'

      true
    else

      false
    end

  end

end

class Numeric

  def negative?
    self < 0
  end

end

class Integer

  def to_yen
    self.to_s.gsub(/(\d)(?=(\d{3})+(?!\d))/, '\1,')
  end

end

class String

  ### http://qiita.com/kumagi/items/04edfb73fd2f5a060510
  ### 大感謝。ありがとう。<(_ _)>
  def to_snake

    self.gsub(/([A-Z]+)([A-Z][a-z])/, '\1_\2')
        .gsub(/([a-z\d])([A-Z])/, '\1_\2')
        .tr("-", "_")
        .downcase
  end

  def to_camel

    self.split("_").map{|w| w[0] = w[0].upcase; w}.join
  end

  def avoid_percent

    self.gsub('%', '%%')
  end

  def normalize_single_quotation

    self.gsub(/'/, '\\\\\0')
  end

  def to_zenkaku

    NKF.nkf("-w -X", self)
  end

  def to_half_width_kana

    NKF.nkf('-Z1 -Ww', self)
  end

  def to_full

    self.tr('-0-9a-zA-Z', '－０-９ａ-ｚＡ-Ｚ')
  end

  def to_half

    self.tr('－０-９ａ-ｚＡ-Ｚ', '-0-9a-zA-Z')
  end

  def katakana_to_hiragana

    NKF.nkf("--hiragana -w", self)
  end

  def to_ascii

    NKF.nkf(
      '-m0Z1 -w --cp932',
      self.gsub(/(?:\p{Hiragana}|\p{Katakana}|[ー－]|[一-龠 々])+/, ' ')
    ).strip
  end

end

class Hash

  def symbolize_keys

    inject({}) do |options, (key, value)|

      value = value.symbolize_keys if defined?(value.symbolize_keys)
      options[(key.to_sym rescue key) || key] = value
      options

    end

  end

  def get_min(key)

    self.sort_by{|k,v| v[key].to_f}.shift[1][key].to_f.round(2)
  end

  def get_max(key)

    self.sort_by{|k,v| v[key].to_f}.reverse.shift[1][key].to_f.round(2)
  end

  def get_avg(key)

    (self.map{|k,v| v[key].to_f}.inject(0.0){|r,i| r+=i.to_i}/self.size).to_f.round(2)
  end

  def to_q

    URI.encode_www_form(self)
  end

end

class Array

  def avg

    if self.size > 0

      (self.inject(0.0){|r,i| r+=i } / self.size).to_i
    else

      0
    end
  end

end
