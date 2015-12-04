/* global siteUrl */
$(function () {
  'use strict'

  var tblOngkir = $('#tbl-ongkir')
    , checkOngkir

  checkOngkir = function (e) {
    var self  = $(this)
      , val   = self.html()
      , tbody = tblOngkir.find('tbody')

    self
      .attr('disabled', 'disabled')
      .html('Loading')

    tblOngkir.addClass('hidden')
    tbody.empty()

    // http://api.jquery.com/jquery.ajax/
    // Asyncronous Javascript and XML
    $.ajax({
      url: siteUrl('shop/shipment'),
      method: 'POST',
      dataType: 'json',
      data: {
        origin:      $('[name="origin"]').val(),
        destination: $('[name="destination"]').val(),
        weight:      $('[name="weight"]').val(),
        courier:     $('[name="courier"]').val()
      },
      error: function (xhr, status, error) {
        var res = xhr.responseText.toJson()
        alert(res.errors.join(' - '))
      }
    }).done(function (out) {
      var costs = out.costs || []

      if (out.errors === undefined && costs.length > 0) {
        $.each(costs, function (i, cost) {
          var service = cost.service+' ('+cost.description+')'
            , price = cost.cost[0].value
            , etd = cost.cost[0].etd || '-';

          tbody.append([
            '<tr>',
              '<td class="acenter">',
                '<input type="radio" name="ongkir" required value="'+price+'" data-paket="'+service+'">',
              '</td>',
              '<td>'+service+'</td>',
              '<td class="acenter">'+etd+'</td>',
              '<td class="aright">'+price+'</td>',
            '</tr>'
          ].join(''))
        })
      } else {
        var errors = 'Maaf! layanan kami tidak dapat menjangkau tempat tinggal anda. Pindah gih!'

        if (out.errors !== undefined) {
          errors = out.errors.join(' - ')
        }

        tbody.append('<tr><td colspan="3" class="acenter">'+errors+'</td></tr>')
      }

      tblOngkir.removeClass('hidden')
    }).always(function () {
      self.removeAttr('disabled')
      self.html(val)
    })

    e.preventDefault()
  }

  $('#btn-ongkir').on('click', checkOngkir)

  var sum = 0

  $('#tbl-produk').bind('keyup change', 'input.qty', function (e) {
    var subs = []
      , wgts = []

    $(this).find('tr').each(function (i) {
      var id  = $(this).data('id')
        , qty = $(this).find('#produk_qty-'+id)
        , prc = $(this).find('#harga-'+id)
        , wgt = $(this).find('#berat-'+id)
        , sub = qty.val() * prc.val()
        , tot = 0
        , spm = 0

      subs[i] = sub
      wgts[i] = qty.val() * wgt.val()

      $('span#subtotal-'+id).html(sub)
      $('input#subtotal-'+id).val(sub)

      $.each(subs, function (i) {
        tot += subs[i]
        spm += wgts[i]
      })

      $('#belanja').val(tot).attr('readonly', '').attr('tabindex', '-1')
      $('[name="weight"]').val(spm)
    })
  })

  tblOngkir.bind('click', 'input[name="ongkir"]', function () {
    var belanja = +$('#belanja').val()
      , ongkir = $(this).find('input[name="ongkir"]:checked')
      , ongkirVal = +ongkir.val()

    sum = belanja + ongkirVal

    $('#kurir').val(ongkir.data('paket'))
    ongkir.attr('checked', true)
    $('#total').val(sum)
    $('#total-s').html(sum)
    $('#potongan').val('0')
    $('#bayar').val(sum)
    $('#kembali').val('0')
  })

  $('select[name="destination"]').on('change', function (e) {
    $('input[name="kota"]').val($(this).find(":checked").html())
  })

  $('#potongan').on('keyup change', function (e) {
    var totalVal = +$('#total').val()
      , ptgVal = +$(this).val()

    if (ptgVal !== 0) {
      sum = totalVal - ptgVal

      $('#total-s').html('<s>'+totalVal+'</s> <span>'+sum+'</span>')
    }
  })

  $('#bayar').on('keyup change', function (e) {
    $('#total').val(sum)
    $('#kembali').val($(this).val() - sum)
  })
})
