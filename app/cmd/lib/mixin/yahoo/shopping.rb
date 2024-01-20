module TWEyes

  module Mixin ### この空間は以下はMix-in専用

    module Yahoo ### 名前空間用なので機能を持たせるとバグるよ

      module Shopping
      end

    end ### module Yahoo [END]

  end ### Mixin [END]

end ### module TWEyes [END]
