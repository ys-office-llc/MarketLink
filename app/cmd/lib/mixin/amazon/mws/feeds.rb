module TWEyes

  module Mixin ### この空間は以下はMix-in専用

    module Amazon ### 名前空間用なので機能を持たせるとバグるよ

      module MWS

        module Feeds

        end ### module Feeds [END]

      end ### module MWS [END]

    end ### module Amazon [END]

  end ### Mixin [END]

end ### module TWEyes [END]
