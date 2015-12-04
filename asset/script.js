'use strict'

// Site Url helper
function siteUrl(permalink) {
  var baseUrl = $('body').data('siteurl') || '/'

  return baseUrl + permalink
}

// Extending String object
// Simplyfing jQuery.parseJSON() method :P
String.prototype.toJson = function () {
  return $.parseJSON(this)
}

// Add left and right trim functionality, like PHP does
// Thanks to: http://stackoverflow.com/a/3840667/881743
String.prototype.rTrim = function (char) {
  var c = char || ' '
    , lastChar = this.length - 1

  if (this.charAt(lastChar) == char) {
    return this.substr(0, lastChar)
  }
}

String.prototype.lTrim = function (char) {
  var c = char || ' '

  if (this.charAt(0) == c) {
    return this.substr(1)
  }
}

$(function () {
  // Disable click on blank link
  $('a[href="#"]').on('click', function (e) {
    e.preventDefault()
  })

  // Data Table Delete button function
  $('.btn-hapus').on('click', function (e) {
    if (!window.confirm($(this).data('confirm-text'))) {
      e.preventDefault()
    }
  })

  // Home Slider
  var left  = 0, width = 930
    , total = width * $('.slide').size()

  $('.slider').css({
    width: total,
    height: 300
  })

  setInterval(function () {
    if (left >= -(total - (width * 2))) {
      left = left - width
    } else {
      left = 0
    }

    $('.slider').css('margin-left', left)
  }, 5000)

  // Jquery UI Tab Trigger
  $('.jqui-tabs').hide()
  $(document).ready(function () {
    $('.jqui-tabs').show().tabs()
  })

  // Jquery UI Datepicker Trigger
  $('.jqui-datepicker').datepicker({ dateFormat: 'dd-mm-yy' })

  // Jquery UI Autocomplete
  $('.jqui-autocomplete').each(function (e) {
    var self = $(this)

    if (typeof self.attr('data-url') !== 'undefined') {
    var remoteSrc = self.data('url')
      , field = self.data('field')
      , resData = {}

      self.autocomplete({
        minLength: 2,
        source: function (request, response) {
          $.ajax({
            url: siteUrl(remoteSrc),
            method: 'POST',
            dataType: 'json',
            data: {
              s: self.val()
            }
          }).done(function (result) {
            var res = []

            if (result.errors !== undefined) {
              response(result.errors)
            } else {
              var dataKey

              $.each(result, function (i, data) {
                dataKey = data[field]
                res.push(dataKey)
                resData[dataKey] = data
              })

              response(res)
            }
          })
        },
        select: function (event, ui) {
          var selfId = self.attr('id')
            , data = resData[ui.item.label]

          if (selfId == 'nama_lengkap') {
            var kota = data.kota.replace(/\s+/g, '-').replace(/\(|\)+/g, '').toLowerCase()

            // console.log(kota)

            $('[name="id_pelanggan"]').val(data.id_pelanggan)
            $('[name="nama_lengkap"]').val(data.nama_lengkap).attr('disabled', true)
            $('[name="alamat"]').html(data.alamat).attr('disabled', true).attr('tabindex', '-1')
            $('[name="kota"]').val(data.kota).attr('disabled', true)
            $('[name="destination"]').attr('disabled', true).attr('tabindex', '-1')
            $('[name="destination"]').find('option#dest-'+kota).attr('selected', '')
            $('[name="telp"]').val(data.telp).attr('disabled', true).attr('tabindex', '-1')
            $('#fieldset-akun').attr('disabled', true).addClass('hidden')
            $('#produk').focus()
          } else if (selfId == 'produk') {
            var idProduct = data.id_produk
              , discount = +data.diskon

            $('#tbl-produk').find('tr.empty').remove()
            $('#tbl-produk').append([
              '<tr id="produk-row-'+idProduct+'" data-id="'+idProduct+'">',
                '<td>'+
                  '<span class="thumb" style="background-image: url('+$('body').data('siteurl')+'asset/uploads/'+data.gambar+');"></span>'+
                  '<input type="hidden" name="produk_id[]" value="'+idProduct+'">'+
                  '<input type="hidden" name="produk_weight[]" value="'+data.berat+'" id="berat-'+idProduct+'">'+
                '</td>',
                '<td>'+data.nama+'</td>',
                '<td class="acenter"><input type="number" min="0" class="full" id="produk_qty-'+idProduct+'" name="produk_qty[]" class="qty"></td>',
                '<td class="aright">'+
                  (discount !== 0 ? discount+'<br><del>'+data.harga+'</del>' : data.harga)+
                  '<input type="hidden" id="harga-'+idProduct+'" name="harga[]" value="'+(discount !== 0 ? discount : data.harga)+'">'+
                '</td>',
                '<td class="aright">'+
                  '<span id="subtotal-'+idProduct+'">0</span>'+
                  '<input id="subtotal-'+idProduct+'" type="hidden" name="subtotal[]">'+
                '</td>',
              '</tr>'
            ].join(''))

            self.val('')
          }

          // console.log(ui.item ? ui : 'nope')
        }
      })
    }
  })

  // Extend jQuery.Validation Messages
  $.extend($.validator.messages, {
    required: 'Field ini harus diisi.',
    remote: 'Silahkan perbaiki field ini.',
    email: 'Silahkan masukan alamat email dengan benar.',
    url: 'Silahkan masukan alamat url dengan benar.',
    date: 'Silahkan masukan tanggal dengan benar.',
    dateISO: 'Silahkan masukan tanggal dengan benar ( ISO ).',
    number: 'Silahkan masukan nomor dengan benar.',
    digits: 'Silahkan masukan digit dengan benar.',
    creditcard: 'Silahkan masukan format kartu kredit dengan benar.',
    equalTo: 'Silahkan masukan lagi nilai yang sama.',
    maxlength: $.validator.format( 'Jumlah karakter tidak boleh lebih dari {0}.' ),
    minlength: $.validator.format( 'Jumlah karakter minimal adalah {0}.' ),
    rangelength: $.validator.format( 'Jumlah karakter yang diperbolehkan adalah {0} sampai {1}.' ),
    range: $.validator.format( 'Nilai yang diperbolehkan adalah {0} sampai {1}.' ),
    max: $.validator.format( 'Silahkan masukan nilai kurang dari atau sama dengan {0}.' ),
    min: $.validator.format( 'Silahkan masukan nilai lebih dari atau sama dengan {0}.' )
  })

  // Initiate jQuery.Validation
  var validator = $('.form').validate()

  // NicEdit
  /* global bkLib, nicEditor, nicEditors */
  $('textarea.full').each(function () {
    var self = $(this)
      , name = self.attr('name')
      , id = self.attr('id')
      , form = self.parents('form')

    bkLib.onDomLoaded(function () {
      var nicedit = new nicEditor({
        iconsPath: $('body').data('siteurl') + 'asset/lib/nicedit-icons.gif',
        buttonList: [
          'fontSize','fontFormat',
          'bold','italic','underline',
          'left','center','right','forecolor',
          'ol','ul',
          'indent','outdent',
          'image','upload',
          'link','unlink'
        ]
      })

      nicedit.panelInstance(id)

      var editor = nicEditors.findEditor(id)
        , main = self.parents('.control-input').find(editor.elm)

      main.keyup(function () {
        var editorVal = editor.getContent()

        if (editorVal !== '' || editorVal != '<br>') {
          self.html(editor.getContent())
        }
      })
    })
  })

  // Form validation on submit
  $('.form').on('submit', function (e) {
    var form = $(this)
      , inputs = form.find('input,textarea,select')

    if (validator.valid()) {
      inputs.each(function () {
        $(this).attr('disabled')
      })
    } else {
      // alert('error')
      // e.preventDefault()
    }

  })
})
